<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesAnalysis extends Model
{
    use HasFactory;

    protected $table = 'sales_analysis';

    protected $fillable = [
        'vendeur_id',
        'period',
        'total_sales',
        'total_returns',
        'total_discounts',
        'net_sales',
    ];

    public function vendeurs()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

}
