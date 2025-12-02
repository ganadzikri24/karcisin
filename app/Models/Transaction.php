<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Izin simpan semua kolom
    protected $guarded = ['id'];

    // Relasi: Transaksi milik User siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Transaksi ini untuk Event apa?
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}