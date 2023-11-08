<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addon extends Model implements TranslatableContract
{
    use HasFactory,SoftDeletes,Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable = ['category_id','price'];
    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
