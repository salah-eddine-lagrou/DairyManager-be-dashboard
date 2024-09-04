<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBalance extends Model
{
    use HasFactory;

    protected $table = 'client_balances';

    protected $fillable = [
        'balance_amount',
        'bl_amount',
        'credit_note_amount',
        'unpaid_amount',
        'description',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
