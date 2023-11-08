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

    protected $fillable = ['department_id'];

    protected $hidden = [
		'created_at',
        'updated_at',
        'deleted_at',
	];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function addons(){
        return $this->hasMany(Addon::class);
    }

    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }

}
