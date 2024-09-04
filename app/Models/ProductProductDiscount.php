<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductProductDiscount extends Model
{
    use HasFactory;

    protected $table = 'product_product_discount';

    protected $fillable = [
        'product_discount_id',
        'product_id',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function productDiscounts()
    {
        return $this->belongsTo(ProductDiscount::class, 'product_discount_id', 'id');
    }

}
