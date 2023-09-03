<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class Product extends Model
{
    use HasFactory,SoftDeletes,Translatable;

    protected $fillable = ['price', 'info', 'description', 'category_id', 'provider_id', 'images'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

}
