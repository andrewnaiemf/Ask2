<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonTranslation extends Model
{
    use HasFactory;
    protected $table = 'addons_translation';
    protected $fillable = ['name'];
    protected $hidden = ['created_at','updated_at','deleted_at'];

    public $timestamps = false;

}
