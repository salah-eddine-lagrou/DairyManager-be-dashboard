<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourneVendeur extends Model
{
    use HasFactory;

    protected $table = 'tourne_vendeur';

    protected $fillable = [
        'tourne_id',
        'vendeur_id',
        'owner'
    ];

    public function tourne()
    {
        return $this->belongsTo(Tourne::class, 'tourne_id', 'id');
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id', 'id');
    }
}
