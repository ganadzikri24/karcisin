<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\EventController;

Auth::routes();

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/event/detail/{id}', [EventController::class, 'publicShow'])->name('public.event.show');

Route::view('/about', 'about')->name('about');

Route::middleware(['auth'])->group(function () {
    
    
    Route::get('/my-tickets', [EventController::class, 'myTickets'])->name('my.tickets');
    
    Route::get('/ticket/{id}/download', [EventController::class, 'downloadTicket'])->name('ticket.download');
    
    Route::get('/event/{id}/checkout', [EventController::class, 'checkout'])->name('event.checkout');
    
    Route::post('/transaction/process', [EventController::class, 'processTransaction'])->name('transaction.store');

    
    Route::get('/home', [EventController::class, 'index'])->name('home');
    
    Route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::post('/event/store', [EventController::class, 'store'])->name('event.store');
    

    Route::get('/event/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{id}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{id}', [EventController::class, 'destroy'])->name('event.destroy');

    Route::get('/event/{id}/manage', [EventController::class, 'show'])->name('event.show');
    
    Route::post('/transaction/{id}/approve', [EventController::class, 'approveTransaction'])->name('transaction.approve');
    
    Route::get('/event/{id}/scan', [EventController::class, 'scanPage'])->name('event.scan');
    Route::post('/scan/verify', [EventController::class, 'verifyTicket'])->name('scan.verify');

    Route::get('/event/{id}/export-excel', [EventController::class, 'exportTickets'])->name('creator.export.excel');
    
    Route::get('/scan', [EventController::class, 'scanPage'])->name('scan.index');

    Route::get('/scan-general', [EventController::class, 'scanGeneral'])->name('scan.index');

    
    Route::get('/developer', [EventController::class, 'developerIndex'])->name('developer.index');
    
    Route::post('/developer/approve/{id}', [EventController::class, 'developerApprove'])->name('developer.approve');
});
