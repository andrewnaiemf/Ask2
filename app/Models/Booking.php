<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id','provider_id','year','month','day','time','notes','status'
    ];

    protected $hidden =[
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function clinicBookings()
    {
        return $this->hasMany(ClinicBooking::class, 'booking_id');
    }


    public function subdepartment()
    {
        return $this->belongsTo(Department::class, 'sub_department_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }


}
