<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Color extends Model
{
    use HasFactory,Translatable;

    protected $fillable = ['value'];

    public $translatedAttributes = ['name'];

    protected $hidden =[
        'deleted_at',
        'created_at',
        'updated_at',
    ];


}
