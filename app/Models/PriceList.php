<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    use HasFactory;

    protected $table = 'price_lists';

    protected $fillable = [
        'code',
        'rank',
        'description',
        'price_list_name_id'
    ];

    public function clients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'price_list_product_details')
                    ->using(ProductStock::class)
                    ->withPivot('code', 'sale_price', 'return_price', 'valid_from', 'valid_to', 'closed');
    }

    public function priceListName(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PriceListName::class, 'price_list_name_id');
    }
}
