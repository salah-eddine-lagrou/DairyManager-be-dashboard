<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceListProductDetails extends Model
{
    use HasFactory;

    protected $table = 'price_list_product_details';

    protected $fillable = [
        'product_id',
        'price_list_id',
        'code',
        'sale_price',
        'return_price',
        'valid_from',
        'valid_to',
        'closed',
    ];

    public function priceList()
    {
        return $this->belongsTo(PriceList::class, 'price_list_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function clientSales()
    {
        return $this->hasMany(ClientSale::class, 'price_list_product_details_id');
    }


}
