<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('event_id')->constrained('events');
            $table->integer('quantity');
            $table->integer('total_price');
            $table->enum('status', ['pending', 'paid', 'rejected'])->default('pending');
            $table->string('payment_proof')->nullable();
            
            // --- KOLOM BARU (Sesuai Request) ---
            $table->string('customer_name');  // Nama Lengkap (Sesuai KTP)
            $table->string('customer_email'); // Email Aktif
            $table->string('customer_phone'); // No HP / WhatsApp
            $table->string('customer_nik');   // Nomor KTP/NIK
            $table->string('bank_name');      // Bank Pengirim
            // -----------------------------------

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};