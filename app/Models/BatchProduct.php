<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchProduct extends Model
{
    use HasFactory;

    protected $table = 'batch_products';

    protected $fillable = [
        'measure_batch',
        'measure_items',
        'weight_batch',
        'batch_product_price',
        'batch_unit_id',
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function batchUnit()
    {
        return $this->belongsTo(Unit::class, 'batch_unit_id');
    }
}
