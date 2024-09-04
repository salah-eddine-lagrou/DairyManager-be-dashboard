<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPayment extends Model
{
    use HasFactory;

    protected $table = 'client_payments';

    protected $fillable = [
        'amount',
        'transaction_date',
        'payment_method',
        'transaction_type',
        'order_id',
        'client_id',
        'code',
        'payment_period',
        'discount',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
