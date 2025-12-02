<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Tiket ini hasil dari transaksi mana?
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}