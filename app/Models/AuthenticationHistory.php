<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticationHistory extends Model
{
    use HasFactory;

    protected $table = 'authentication_histories';

    protected $fillable = [
        'auth_status',
        'description',
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_authentication_histories')->withTimestamps();
    }

}
