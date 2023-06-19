<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name','address','user_id','lat','lng','phone','info'
    ];

    protected $hidden =[
        'deleted_at',
        'created_at',
        'updated_at',
    ];
}
