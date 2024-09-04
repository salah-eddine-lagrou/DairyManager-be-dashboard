<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'code',
        'total_totals',
        'amount_total',
        'client_id',
        'vendeur_id',
        'order_status',
        'order_payment_status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function clientPayment()
    {
        return $this->hasMany(ClientPayment::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'client_sales')
                    ->using(ClientSale::class)
                    ->withPivot('total', 'measure', 'sale_date', 'discount_sale_id', 'price_list_product_details_id');
    }
}
