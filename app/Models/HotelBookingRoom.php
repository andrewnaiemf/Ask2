<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelBookingRoom extends Model
{
    use HasFactory;

    public $table = 'booking_rooms';

    public function room(){
        return $this->belongsTo(Room::class,'room_id');
    }

}
