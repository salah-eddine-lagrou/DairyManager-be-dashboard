<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchProductClientSale extends Model
{
    use HasFactory;

    protected $table = 'batch_product_client_sales';

    protected $fillable = [
        'measure_batches',
        'client_sale_id'
    ];

    public function clientSale()
    {
        return $this->belongsTo(ClientSale::class, 'client_sale_id', 'id');
    }
}
