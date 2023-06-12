<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicBooking extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'clinic_bookings';


    protected $fillable = [
        'doctor_name',
        'cost',
        'clinic_id',
    ];

    protected $hidden =[
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
