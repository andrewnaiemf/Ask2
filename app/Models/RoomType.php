<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
class RoomType extends Model
{
    use HasFactory,SoftDeletes,Translatable;

    public $table = 'room_types';

    public $translatedAttributes = ['name'];

    public $fillable = ['icon_url'];

    protected $hidden = [
		'created_at',
        'updated_at',
        'deleted_at',
	];
}
