<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    use HasFactory;

    protected $table = 'product_discounts';

    protected $fillable = [
        'discount_rate',
        'discount_type',
        'start_date',
        'end_date',
        'description',
        'status'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_id');
    }
}
