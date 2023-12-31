<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    use HasFactory;
    protected $table = 'category_translation';
    protected $fillable = ['name'];
    protected $hidden = ['created_at','updated_at','deleted_at'];

    public $timestamps = false;
}
