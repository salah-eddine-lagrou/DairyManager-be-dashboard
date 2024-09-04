<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $table = 'agencies';

    protected $fillable = [
        'code',
        'name',
        'location',
        'created_by_id',
        'modified_by_id',
        'status',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'modified_by_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
}
