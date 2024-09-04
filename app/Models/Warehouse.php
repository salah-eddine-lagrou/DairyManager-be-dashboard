<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $fillable = [
        'code',
        'name',
        'location',
        'created_by_id',
        'modified_by_id',
        'status',
        'warehouse_type',
        'agency_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'modified_by_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}
