<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelServicetranslation extends Model
{
    use HasFactory;

    protected $table = 'hotel_services_translations';
    protected $fillable = ['name'];

    public $timestamps = false;
}
