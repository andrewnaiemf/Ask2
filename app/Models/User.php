<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable  implements JWTSubject
{
	use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'account_type',
        'admin_group_id',
        'name',
        'phone',
        'password',
        'profile',
        'uuid','city_id',
        'device_token',
        'uuid',
        'email',
        'can_share_news',
        'lng'
	];

	protected $deleted_at = 'deleted_at';
    protected $appends = ['rating'];

    //to arrange the user aattribute and push the id to the beginning of the array
    public function toArray()
    {
        $userArray = parent::toArray();
        $userArray = array_merge(['id' => $this->id], $userArray);
        return $userArray;
    }


	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
        'updated_at',
        'created_at',
        'email',
        'email_verified_at',
        'admin_group_id',
        'deleted_at'
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];


        /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function getDeviceTokenAttribute($value)
    {
        // Check if the value is already an array
        if (is_array($value)) {
            return $value;
        }

        // Check if the value is a JSON string
        if (is_string($value) && json_decode($value) !== null) {
            return json_decode($value, true);
        }

        // If none of the above conditions match, return an empty array
        return [];

    }



	public function getCreatedAtAttribute($date) {
		return empty($date) ? $date : date('Y-m-d', strtotime($date));
	}

	public function getUpdatedAtAttribute($date) {
		return empty($date) ? $date : date('Y-m-d', strtotime($date));
	}

	public function getDeletedAtAttribute($date) {
		return empty($date) ? null : date('Y-m-d', strtotime($date));
	}

	public function getEmailVerifiedAtAttribute($date) {
		return empty($date) ? null : date('Y-m-d', strtotime($date));
	}

    public function getRatingAttribute()
    {
        $average_rating =  $this->ratings()->avg('rate');
        return number_format($average_rating, 2);

    }

	public function admingroup() {
		return $this->belongsTo(AdminGroup::class, 'admin_group_id');
	}

    public function provider()
    {
        return $this->hasOne(Provider::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function bookings(){
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function orders(){
        return $this->hasMany(Order::class, 'user_id');
    }

    public function addresses(){
        return $this->hasMany(Address::class, 'user_id');
    }

}
