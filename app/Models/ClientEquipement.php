<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientEquipement extends Model
{
    use HasFactory;

    protected $table = 'client_equipement';

    protected $fillable = [
        'client_id',
        'equipement_id',
    ];

    public function clients()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function equipements()
    {
        return $this->belongsTo(Equipement::class, 'equipement_id', 'id');
    }
}
