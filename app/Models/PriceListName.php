<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceListName extends Model
{
    use HasFactory;

    protected $table = 'price_list_names';

    protected $fillable = [
        'name',
        'description'
    ];

    public function priceLists(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PriceList::class);
    }
}
