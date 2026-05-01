<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketApproved;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\TicketExport;       

class EventController extends Controller
{

    public function index()
    {
        if (Auth::user()->role !== 'creator') {
            return redirect('/')->with('error', 'Anda bukan Creator!');
        }
        $myEvents = Event::where('created_by', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('creator.dashboard', compact('myEvents'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'creator') {
            return redirect('/')->with('error', 'Akses Ditolak.');
        }
        return view('creator.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'creator') {
            return abort(403);
        }

        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'description' => 'required',
            'event_date' => 'required',
            'location' => 'required',
            'price' => 'required|numeric',
            'quota' => 'required|numeric',
            'banner' => 'required|image|max:2048',
        ]);

        $bannerPath = $request->file('banner')->store('banners', 'public');

        Event::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'location' => $request->location,
            'price' => $request->price,
            'quota' => $request->quota,
            'banner' => $bannerPath,
            'created_by' => Auth::id(),
            'status' => 'pending',
        ]);

        return redirect()->route('home')->with('success', 'Event berhasil diajukan! Menunggu persetujuan Developer.');
    }

    public function edit($id)
    {
        $event = Event::where('id', $id)->where('created_by', Auth::id())->firstOrFail();
        return view('creator.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::where('id', $id)->where('created_by', Auth::id())->firstOrFail();

        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'description' => 'required',
            'event_date' => 'required',
            'location' => 'required',
            'price' => 'required|numeric',
            'quota' => 'required|numeric',
            'banner' => 'nullable|image|max:2048',
        ]);

        $event->name = $request->name;
        $event->category = $request->category;
        $event->description = $request->description;
        $event->event_date = $request->event_date;
        $event->location = $request->location;
        $event->price = $request->price;
        $event->quota = $request->quota;

        if ($request->hasFile('banner')) {
            
            $bannerPath = $request->file('banner')->store('banners', 'public');
            $event->banner = $bannerPath;
        }


        $event->save();

        return redirect()->route('event.show', $id)->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $event = Event::where('id', $id)->where('created_by', Auth::id())->firstOrFail();

        $transactionIds = \App\Models\Transaction::where('event_id', $event->id)->pluck('id');
        
        Ticket::whereIn('transaction_id', $transactionIds)->delete();

        \App\Models\Transaction::where('event_id', $event->id)->delete();

        $event->delete();

        return redirect()->route('home')->with('success', 'Event berhasil dihapus.');
    }

    public function show($id)
    {
        $event = Event::where('id', $id)->where('created_by', Auth::id())->firstOrFail();
        
        $history = Ticket::whereHas('transaction', function($q) use ($id) {
            $q->where('event_id', $id);
        })->where('is_checked_in', true)->orderBy('updated_at', 'desc')->get();

        return view('creator.show', compact('event', 'history'));
    }

    public function exportTickets($id)
        {
        $event = Event::where('id', $id)->where('created_by', Auth::id())->firstOrFail();

        $fileName = 'Data_Tiket_' . Str::slug($event->name) . '_' . date('Ymd') . '.xlsx';

        return Excel::download(new TicketExport($event->id), $fileName);
        }

    public function publicShow($id)
    {
        $event = Event::where('id', $id)->where('status', 'approved')->firstOrFail();
        return view('event_detail', compact('event'));
    }

    public function checkout($id)
    {
        $event = Event::where('id', $id)->where('status', 'approved')->firstOrFail();
        return view('checkout', compact('event'));
    }

    public function processTransaction(Request $request)
    {
        $request->validate([
            'event_id' => 'required',
            'quantity' => 'required|numeric|min:1',
            'payment_proof' => 'required|image|max:2048',
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'customer_phone' => 'required',
            'customer_nik' => 'required',
            'bank_name' => 'required',
        ]);

        $event = Event::findOrFail($request->event_id);
        $totalPrice = $event->price * $request->quantity;
        $proofPath = $request->file('payment_proof')->store('payments', 'public');

        \App\Models\Transaction::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_proof' => $proofPath,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_nik' => $request->customer_nik,
            'bank_name' => $request->bank_name,
        ]);

        $event->quota = $event->quota - $request->quantity;
        $event->save();

        return redirect()->route('my.tickets')->with('success', 'Pembelian berhasil! Menunggu verifikasi panitia.');
    }

    public function myTickets()
    {
        $tickets = Ticket::whereHas('transaction', function($query) {
            $query->where('user_id', Auth::id());
        })->with(['transaction.event'])->latest()->get();
        return view('my_tickets', compact('tickets'));
    }

    public function downloadTicket($id)
    {
        $ticket = Ticket::with('transaction.event', 'transaction.user')->findOrFail($id);
        $pdf = Pdf::loadView('pdf.ticket', compact('ticket'));
        $pdf->setOption(['isRemoteEnabled' => true]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('E-Ticket Karcisin - ' . $ticket->unique_code . '.pdf');
    }

    public function approveTransaction($id)
    {
        $transaction = \App\Models\Transaction::with('event', 'user')->findOrFail($id);
        $transaction->status = 'paid';
        $transaction->save();

        // Generate Tiket
        $existingTickets = Ticket::where('transaction_id', $transaction->id)->count();
        if($existingTickets == 0) {
            for ($i = 0; $i < $transaction->quantity; $i++) {
                Ticket::create([
                    'transaction_id' => $transaction->id,
                    'unique_code' => 'TIKET-' . Str::upper(Str::random(10)),
                    'is_checked_in' => false,
                ]);
            }
        }

        try {
            $ticket = Ticket::where('transaction_id', $transaction->id)->first();
            $pdf = Pdf::loadView('pdf.ticket', compact('ticket'));
            $pdf->setOption(['isRemoteEnabled' => true]);
            Mail::to($transaction->customer_email)->send(new TicketApproved($transaction, $pdf->output()));
        } catch (\Exception $e) {
        }

        return back()->with('success', 'Pembayaran diterima! Tiket diterbitkan & Email terkirim.');
    }

    public function scanPage($id)
    {
        $event = Event::where('id', $id)->where('created_by', Auth::id())->firstOrFail();
        
        $history = Ticket::whereHas('transaction', function($q) use ($id) {
            $q->where('event_id', $id);
        })->where('is_checked_in', true)->orderBy('updated_at', 'desc')->get();

        return view('creator.scan', compact('event', 'history'));
    }

    public function scanGeneral()
    {
        $myEvents = Event::where('created_by', Auth::id())->where('status', 'approved')->get();
        return view('creator.scan_general', compact('myEvents'));
    }

    public function verifyTicket(Request $request)
    {
        $ticket = Ticket::with('transaction.event')
            ->where('unique_code', $request->qr_code)
            ->whereHas('transaction', function($q) use ($request) {
                if($request->event_id) {
                    $q->where('event_id', $request->event_id);
                }
            })->first();

        if (!$ticket) {
            return response()->json(['status' => 'error', 'message' => 'Tiket Tidak Ditemukan / Salah Event!']);
        }

        if ($ticket->is_checked_in) {
            return response()->json(['status' => 'error', 'message' => 'GAGAL! Tiket Sudah Dipakai.']);
        }

        $ticket->is_checked_in = true;
        $ticket->save();

        return response()->json([
            'status' => 'success', 
            'message' => 'BERHASIL! Silakan Masuk.',
            'detail' => $ticket->transaction->customer_name
        ]);
    }


    public function developerIndex()
    {
        if (Auth::user()->email !== 'ganadzikri@gmail.com') {
            return abort(403, 'Hanya Developer yang boleh masuk sini!');
        }

        $pendingEvents = Event::where('status', 'pending')->with('creator')->get();
        return view('developer.index', compact('pendingEvents'));
    }

    public function developerApprove($id)
    {
        if (Auth::user()->email !== 'ganadzikri@gmail.com') {
            return abort(403);
        }

        $event = Event::findOrFail($id);
        $event->status = 'approved';
        $event->save();

        return back()->with('success', 'Event berhasil di-Approve dan sekarang TAYANG di halaman depan!');
    }
}
