<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedTranslation extends Model
{
    use HasFactory;

    protected $table = 'beds_translation';
    protected $fillable = ['name'];

    public $timestamps = false;
}
