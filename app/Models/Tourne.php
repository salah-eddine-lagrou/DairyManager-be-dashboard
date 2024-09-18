<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tourne extends Model
{
    use HasFactory;

    protected $table = 'tournes';

    protected $fillable = [
        'status'
    ];

    public function vendeurs()
    {
        return $this->belongsToMany(User::class, 'tourne_vendeur')
                    ->using(TourneVendeur::class)
                    ->withPivot('owner', 'status');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'tourne_id');
    }
}
