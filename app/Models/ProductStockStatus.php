<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockStatus extends Model
{
    use HasFactory;

    protected $table = 'product_stock_status';

    protected $fillable = [
        'status',
        'description'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
