<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\EventController;

// Route Bawaan Auth (Login/Register/Logout)
Auth::routes();

// ==========================================
// HALAMAN PUBLIK (Bisa Diakses Tanpa Login)
// ==========================================
// Landing Page (Menampilkan Event Unggulan, Konser, dll)
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Detail Deskripsi Event (Sebelum Checkout)
Route::get('/event/detail/{id}', [EventController::class, 'publicShow'])->name('public.event.show');

// Halaman Tentang Karcisin
Route::view('/about', 'about')->name('about');


// ==========================================
// KHUSUS USER LOGIN (Middleware Auth)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // --- FITUR USER BIASA (PEMBELI) ---
    
    // Halaman Tiket Saya
    Route::get('/my-tickets', [EventController::class, 'myTickets'])->name('my.tickets');
    
    // Download Tiket PDF
    Route::get('/ticket/{id}/download', [EventController::class, 'downloadTicket'])->name('ticket.download');
    
    // Checkout Event
    Route::get('/event/{id}/checkout', [EventController::class, 'checkout'])->name('event.checkout');
    
    // Proses Pembayaran (Simpan Transaksi)
    Route::post('/transaction/process', [EventController::class, 'processTransaction'])->name('transaction.store');


    // --- FITUR CREATOR (PANITIA) ---
    
    // Dashboard Creator
    Route::get('/home', [EventController::class, 'index'])->name('home');
    
    // Buat Event Baru (Create & Store)
    Route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::post('/event/store', [EventController::class, 'store'])->name('event.store');
    

    // FITUR EDIT & HAPUS EVENT (BARU)
    Route::get('/event/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{id}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{id}', [EventController::class, 'destroy'])->name('event.destroy');

    // Kelola Event (Detail & List Peserta)
    Route::get('/event/{id}/manage', [EventController::class, 'show'])->name('event.show');
    
    // Approve Pembayaran User
    Route::post('/transaction/{id}/approve', [EventController::class, 'approveTransaction'])->name('transaction.approve');
    
    // Scanner Tiket Per Event
    Route::get('/event/{id}/scan', [EventController::class, 'scanPage'])->name('event.scan');
    Route::post('/scan/verify', [EventController::class, 'verifyTicket'])->name('scan.verify');

    //Download Excel
    Route::get('/event/{id}/export-excel', [EventController::class, 'exportTickets'])->name('creator.export.excel');
    
    // --- TAMBAHAN PENTING (SCANNER UMUM) ---
    // Tambahkan ini agar error 'scan.index not defined' hilang
    Route::get('/scan', [EventController::class, 'scanPage'])->name('scan.index');

    Route::get('/scan-general', [EventController::class, 'scanGeneral'])->name('scan.index');

    // --- FITUR DEVELOPER (ADMIN KHUSUS) ---
    
    // Halaman List Event Pending
    Route::get('/developer', [EventController::class, 'developerIndex'])->name('developer.index');
    
    // Aksi Approve Event agar Tayang
    Route::post('/developer/approve/{id}', [EventController::class, 'developerApprove'])->name('developer.approve');
});
