<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;

    protected $table = 'equipements';

    protected $fillable = [
        'name',
        'code',
        'quantity',
        'equipement_category_id',
        'equipement_state',
    ];

    public function category()
    {
        return $this->belongsTo(EquipementCategory::class, 'equipement_category_id');
    }

    public function client()
    {
        return $this->belongsToMany(Client::class, 'equipement_id');
    }
}
