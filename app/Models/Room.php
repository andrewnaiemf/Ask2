<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'provider_id',
        'room_type_id',
        'numbers',
        'adults',
        'kids',
        'outdoor',
        'images',
        'cost',
    ];

    protected $hidden =[
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function beds()
    {
        return $this->belongsToMany(Bed::class, 'bed_room')->withTimestamps();
    }
}
