<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'provider_id', 'day_of_week', 'start_time', 'end_time'
    ];

    protected $table = 'provider_schedule';

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

}
