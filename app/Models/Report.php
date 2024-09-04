<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'content',
        'user_id',
        'report_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function report_type()
    {
        return $this->belongsTo(ReportType::class, 'report_type_id');
    }
}
