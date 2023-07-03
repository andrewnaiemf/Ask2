<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingDetails extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'booking_id','year','month','day','time'
    ];

    protected $hidden =[
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function booking(){
        return $this->belongsTo(Booking::class);
    }
}
