<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Locale;

class ClinicSchedule extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'clinic_schedule';

    protected $fillable = [
        'provider_id','clinic_id','day_of_week'
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


    public function clinicScheduleDoctors()
    {
        return $this->hasMany(ClinicScheduleDoctor::class);
    }

    public function schedules()
    {
        return $this->hasManyThrough(ClinicSchedule::class, Clinic::class, 'provider_id', 'clinic_id');
    }


}
