<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = [
        'code',
        'name',
        'description'
    ];

    public function subcategories()
    {
        return $this->hasMany(ProductSubcategory::class, 'product_category_id');
    }
}
