<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchProductStock extends Model
{
    use HasFactory;

    protected $table = 'batch_product_stock';

    protected $fillable = [
        'product_stock_id',
        'responsable_measure_batches',
        'magasinier_measure_batches',
        'measure_batches'
    ];

    public function productStock()
    {
        return $this->belongsTo(ProductStock::class);
    }

}
