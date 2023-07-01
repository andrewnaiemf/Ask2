<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRating extends Model
{
    use HasFactory;

    protected $table = 'provider_hotel_rating';

    public $fillable = ['rating'];

    protected $hidden = [
		'created_at',
        'updated_at',
        'deleted_at',
	];
}
