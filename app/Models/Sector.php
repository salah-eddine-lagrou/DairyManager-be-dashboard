<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $table = 'sectors';

    protected $fillable = [
        'code',
        'name',
        'description',
        'zone_id'
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
