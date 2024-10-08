<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientCategory extends Model
{
    use HasFactory;

    protected $table = 'client_categories';

    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    public function subcategories()
    {
        return $this->hasMany(ClientSubcategory::class);
    }
}
