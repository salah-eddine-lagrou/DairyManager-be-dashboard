<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $table = 'requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'reason',
        'location',
        'modified_by_id',
        'warehouse_id',
        'vendeur_id',
        'responsable_id',
        'magasinier_id',
        'status_request',
        'client_id',
        'stock_id',
    ];

    public function modifier()
    {
        return $this->belongsTo(User::class, 'modified_by_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function magasinier()
    {
        return $this->belongsTo(User::class, 'magasinier_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}

