<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSubcategory extends Model
{
    use HasFactory;

    protected $table = 'client_subcategories';

    protected $fillable = [
        'name',
        'code',
        'description',
        'client_category_id'
    ];

    public function client_category()
    {
        return $this->belongsTo(ClientCategory::class, 'client_category_id', 'id');
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
