<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSale extends Model
{
    use HasFactory;

    protected $table = 'client_sales';

    protected $fillable = [
        'total',
        'measure_items',
        'total_measures',
        'sale_date',
        'product_id',
        'order_id',
        'discount_sale_id',
        'price_list_product_details_id',
        'batch_product_client_sale_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function discountSale()
    {
        return $this->belongsTo(DiscountSale::class, 'discount_sale_id');
    }

    public function priceListProductDetails()
    {
        return $this->belongsTo(PriceListProductDetails::class, 'price_list_product_details_id');
    }

    public function batchProductClientSale()
    {
        return $this->hasOne(BatchProductClientSale::class, 'batch_product_client_sale_id');
    }
}
