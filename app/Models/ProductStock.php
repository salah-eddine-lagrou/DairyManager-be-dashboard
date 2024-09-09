<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;

    protected $table = 'product_stock';

    protected $fillable = [
        'stock_id',
        'product_id',
        'product_stock_status',
        'batch_product_stock_id',
        'approved_status',
        'responsable_measure',
        'magasinier_measure',
        'measure_items',
        'total_measures'
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function batchProductStock()
    {
        return $this->hasOne(BatchProductStock::class);
    }
}
