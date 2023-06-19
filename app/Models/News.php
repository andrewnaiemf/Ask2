<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Json;

class News extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'city_id',
        'lat',
        'lng',
        'title',
        'url',
        'phone',
        'whatsapp_phone',
        'images',
        'content'
    ];

    protected $casts = [
        'images' => 'array',
        'content' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}



