<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    protected $fillable = [
        'vendeur_id',
        'quantity',
        'movement_type',
        'status_stock',
        'vendeur_transfert_id',
        'warehouse_id',
        'code',
        'load_type',
    ];

    // Define relationships if needed
    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function vendeurTransfert()
    {
        return $this->belongsTo(User::class, 'vendeur_transfert_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_stock')
                    ->using(ProductStock::class)
                    ->withPivot('product_stock_status', 'batch_product_stock_id', 'measure_items', 'total_measures');
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}
