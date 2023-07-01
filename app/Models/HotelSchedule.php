<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelSchedule extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'hotel_schedules';

    protected $fillable = [
        'provider_id','arrival_start_time','arrival_end_time','departure_start_time','departure_end_time'
    ];

    protected $hidden =[
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
