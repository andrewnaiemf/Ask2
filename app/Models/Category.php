<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model implements TranslatableContract
{
    use HasFactory,SoftDeletes,Translatable;

    public $translatedAttributes = ['name'];


    protected $hidden = [
		'created_at',
        'updated_at',
        'deleted_at',
	];

    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }

}
