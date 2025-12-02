<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Jurus Aman: Izinkan semua kolom diisi kecuali ID
    protected $guarded = ['id'];

    // Relasi ke User (Creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke Transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}