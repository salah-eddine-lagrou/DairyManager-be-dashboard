<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;

    protected $table = 'dashboards';

    protected $fillable = [
        'configuration',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
