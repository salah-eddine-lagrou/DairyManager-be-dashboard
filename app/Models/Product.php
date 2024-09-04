<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'code',
        'name',
        'description',
        'created_by_id',
        'modified_by_id',
        'product_subcategory_id',
        'unit_id',
        'weight',
        'price_ht',
        'tax_id',
        'price_ttc',
        'status',
        'product_stock_status_id',
        'batch_product_id',
        'image',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(ProductSubcategory::class, 'product_subcategory_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tva::class, 'tax_id');
    }

    public function ProductStockStatus()
    {
        return $this->belongsTo(ProductStockStatus::class, 'product_stock_status_id');
    }

    public function stocks()
    {
        return $this->belongsToMany(Stock::class, 'product_stock')
                    ->using(ProductStock::class)
                    ->withPivot('unit_id', 'measure');
    }

    public function priceLists()
    {
        return $this->belongsToMany(PriceList::class, 'price_list_product_details')
                    ->using(ProductStock::class)
                    ->withPivot('code', 'sale_price', 'return_price', 'valid_from', 'valid_to', 'closed');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'client_sales')
                    ->using(ClientSale::class)
                    ->withPivot('total', 'measure', 'sale_date', 'discount_sale_id', 'price_list_product_details_id');
    }

    public function batchProduct()
    {
        return $this->hasOne(BatchProduct::class, 'batch_product_id');
    }

    public function productDiscounts()
    {
        return $this->belongsToMany(ProductDiscount::class, 'product_discount_id');
    }
}
