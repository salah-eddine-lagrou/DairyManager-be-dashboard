<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'code',
        'name',
        'phone',
        'plafond_vendeur',
        'pda_code_access',
        'printer_code',
        'non_tolerated_sales_block',
        'credit_limit',
        'username',
        'created_by_id',
        'modified_by_id',
        'role_id',
        'status',
        'responsable_id',
        'magasinier_id',
        'agency_id',
        'warehouse_id',
        'zone_id',
        'sector_id',
        'email',
        'email_verified_at',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

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

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function stocksAsVendeur()
    {
        return $this->hasMany(Stock::class, 'vendeur_id');
    }

    public function stocksAsVendeurTransfert()
    {
        return $this->hasMany(Stock::class, 'vendeur_transfert_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'vendeur_id');
    }

    public function requestsAsVendeur()
    {
        return $this->hasMany(Request::class, 'vendeur_id');
    }

    public function requestsAsResponsable()
    {
        return $this->hasMany(Request::class, 'responsable_id');
    }

    public function requestsAsMagasinier()
    {
        return $this->hasMany(Request::class, 'magasinier_id');
    }

    public function tournes()
    {
        return $this->belongsToMany(Tourne::class, 'tourne_vendeur')
            ->using(TourneVendeur::class)
            ->withPivot('owner', 'status');
    }

    // Responsable has many Vendeurs
    public function vendeurs()
    {
        return $this->hasMany(User::class, 'responsable_id');
    }

    // Magasinier has many Responsables
    public function responsables()
    {
        return $this->hasMany(User::class, 'magasinier_id');
    }

    // Vendeur belongs to one Responsable
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    // Responsable belongs to one Magasinier
    public function magasinier()
    {
        return $this->belongsTo(User::class, 'magasinier_id');
    }

    public function dashboards()
    {
        return $this->hasMany(Dashboard::class, 'user_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'user_id');
    }

    public function salesAnalysis()
    {
        return $this->hasMany(SalesAnalysis::class, 'vendeur_id');
    }

    public function operationHistories()
    {
        return $this->hasMany(OperationHistory::class, 'user_id');
    }

    // * users could create those ...
    public function agencyCreators()
    {
        return $this->hasMany(Agency::class, 'created_by_id');
    }
    public function agencyModifiers()
    {
        return $this->hasMany(Agency::class, 'modified_by_id');
    }

    public function clientCreators()
    {
        return $this->hasMany(Client::class, 'created_by_id');
    }
    public function clientModifiers()
    {
        return $this->hasMany(Client::class, 'modified_by_id');
    }

    public function productCreators()
    {
        return $this->hasMany(Product::class, 'created_by_id');
    }
    public function productModifiers()
    {
        return $this->hasMany(Product::class, 'modified_by_id');
    }

    public function warehouseCreators()
    {
        return $this->hasMany(Warehouse::class, 'created_by_id');
    }
    public function warehouseModifiers()
    {
        return $this->hasMany(Warehouse::class, 'modified_by_id');
    }



    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
