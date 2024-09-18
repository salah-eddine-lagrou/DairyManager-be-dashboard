<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'code',
        'qr_client',
        'name',
        'email',
        'ice',
        'city',
        'agency_id',
        'client_subcategory_id',
        'warehouse_id',
        'zone_id',
        'sector_id',
        'contact_name',
        'phone',
        'address',
        'tour_assignment_commercial',
        'client_assignment_commercial',
        'price_list_id',
        'credit_limit',
        'credit_note_balance',
        'global_limit',
        'location',
        'location_gps_coordinates',
        'visit',
        'offert',
        'notification',
        'created_by_id',
        'modified_by_id',
        'status',
        'tourne_id'
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function clientSubcategory()
    {
        return $this->belongsTo(ClientSubcategory::class, 'client_subcategory_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    public function priceList()
    {
        return $this->belongsTo(PriceList::class, 'price_list_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by_id');
    }

    public function tourne()
    {
        return $this->belongsTo(Tourne::class, 'tourne_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    public function clientPayments()
    {
        return $this->hasMany(ClientPayment::class, 'client_id');
    }

    public function clientBalances()
    {
        return $this->hasMany(ClientBalance::class, 'client_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'client_id');
    }

    public function equipements()
    {
        return $this->belongsToMany(Equipement::class, 'client_equipement', 'client_id', 'equipement_id');
    }

}
