<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Locales;
class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar','name_en','name_eu'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function toArray()
    {

        $lang = app(Locales::class)->current();

        $array['id'] =$this['id'];
        $array['name'] =$this->{'name_'.$lang};

        return $array;
    }

    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'provider_clinic');
    }

    public function schedules()
    {
        return $this->hasMany(ClinicSchedule::class);
    }

}
