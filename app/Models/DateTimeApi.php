<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DateTimeApi extends Model
{
    protected $table = 'date_time_api';
    use HasFactory;
    protected $fillable = [
        'user_id',
        'action',
        'gap',
        'start_date',
        'start_timezone',
        'end_date',
        'end_timezone',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
