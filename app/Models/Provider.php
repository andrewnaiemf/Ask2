<?php

namespace App\Models;

use Astrotomic\Translatable\Locales;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasFactory,SoftDeletes;


    protected $fillable = [
        'user_id', 'commercial_register', 'location', 'info', 'service', 'email',
        'latitude', 'longitude', 'facebook_link', 'instagram_link', 'twitter_link',
        'snapchat_link', 'linkedin_link', 'department_id', 'subdepartment_id',
        'open_all_time'
    ];

    protected $dates = ['deleted_at'];

	protected $hidden = [
        'deleted_at','updated_at','created_at','email', 'latitude', 'longitude','info','service',
        'facebook_link', 'instagram_link', 'twitter_link','snapchat_link',
        'linkedin_link','department_id', 'subdepartment_id','open_all_time'
	];

    protected $appends = ['communications', 'description', 'rating'];


    public function getRatingAttribute()
    {
        if (auth()->user()) {
            $average_rating =  $this->ratings()->avg('rate');
            return number_format($average_rating, 2);
        }
        return '0' ;
    }

    public function getCommunicationsAttribute()
    {
        $lang = app(Locales::class)->current();

        $user = User::with('city')
        ->where('id', $this->user_id)
        ->first();

        $fields = [
            'facebook_link',
            'instagram_link',
            'twitter_link',
            'snapchat_link',
            'linkedin_link',
            'phone',
            'email',
            'city',
            'longitude',
            'latitude',
        ];

        $isNull = true;

        foreach ($fields as $field) {
            if (!is_null($this->$field)) {

                $isNull = false;
                break;
            }
        }

        if ($isNull) {
            return null;
        }

        return (object) [
            'facebook_link' => $this->facebook_link,
            'instagram_link' => $this->instagram_link,
            'twitter_link' => $this->twitter_link,
            'snapchat_link' => $this->snapchat_link,
            'linkedin_link' => $this->linkedin_link,
            'phone' => $user->phone,
            'email' => $user->email,
            'city' => $user->city->{'name_'.$lang},
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
        ];
    }

    public function getDescriptionAttribute()
    {

        $fields = [
            'info',
            'service',
        ];

        $isNull = true;

        foreach ($fields as $field) {
            if (!is_null($this->$field)) {
                $isNull = false;
                break;
            }
        }

        if ($isNull) {
            return null;
        }
        return (object) [
            'info' => $this->info,
            'service' => $this->service,
        ];
    }

    public function toArray()
    {
        $array = parent::toArray();

        $array['communications'] = $this->communications;
        // $array['description'] = $this->description;

        return $array;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id' );
    }

    public function subdepartment()
    {
        return $this->belongsTo(Department::class, 'subdepartment_id');
    }

    public function documents()
    {
        return $this->hasMany(DocumentProvider::class);
    }

    public function images()
    {
        return $this->hasMany(DocumentProvider::class)->where('name','describe_image');
    }


    public function ratings()
    {
        return $this->hasMany(Rating::class, 'rated_user_id');
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'provider_clinic');
    }

    public function schedules()
    {
        return $this->hasManyThrough(ClinicSchedule::class, Clinic::class);
    }

}
