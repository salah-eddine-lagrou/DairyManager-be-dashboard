<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $table = 'zones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'warehouse_id',
    ];

    /**
     * Get the warehouse that owns the zone.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function sectors()
    {
        return $this->hasMany(Sector::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
