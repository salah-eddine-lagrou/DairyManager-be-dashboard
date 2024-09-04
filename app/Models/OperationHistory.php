<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationHistory extends Model
{
    use HasFactory;

    protected $table = 'operations_history';

    protected $fillable = [
        'operation_type',
        'operation_details',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
