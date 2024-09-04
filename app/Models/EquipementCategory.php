<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipementCategory extends Model
{
    use HasFactory;

    protected $table = 'equipement_categories';

    protected $fillable = [
        'category_name',
        'description',
    ];

    public function equipements()
    {
        return $this->hasMany(Equipement::class, 'equipement_category_id');
    }
}
