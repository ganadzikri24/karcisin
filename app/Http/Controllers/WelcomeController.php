<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        // Fitur Cari (Poin 1.5)
        if ($request->has('search')) {
            $events = Event::where('status', 'approved')
                           ->where('name', 'LIKE', '%' . $request->search . '%')
                           ->get();
            return view('search_result', compact('events'));
        }

        // Ambil Event berdasarkan Kategori (Hanya yang Approved) - (Poin 3)
        $unggulan = Event::where('status', 'approved')->orderBy('created_at', 'desc')->take(5)->get();
        $konser = Event::where('status', 'approved')->where('category', 'Konser')->get();
        $wisata = Event::where('status', 'approved')->where('category', 'Wisata')->get();
        
        return view('welcome', compact('unggulan', 'konser', 'wisata'));
    }
}