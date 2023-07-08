<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelBookingDetails extends Model
{
    use HasFactory;

    public $table = 'hotel_booking_details';



    public function roomBookingDetail(){
        return $this->hasOne(HotelBookingRoom::class);
    }
}
