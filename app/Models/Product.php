<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class Product extends Model
{
    use HasFactory,SoftDeletes,Translatable;

    protected $fillable = ['price', 'description', 'category_id', 'provider_id', 'images', 'stock'];

    public $translatedAttributes = ['name','info'];

    public function getImagesAttribute($value)
    {
        return json_decode($value, true);
    }

    protected $hidden =[
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function attribute()
    {
        return $this->hasOne(ProductAttribute::class);
    }

    public function colors(){
        return $this->belongsToMany(Color::class);
    }

}
