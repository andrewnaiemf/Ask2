<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTypeTranslation extends Model
{
    use HasFactory;

    protected $table = 'room_types_translation';
    protected $fillable = ['name'];

    public $timestamps = false;
}
