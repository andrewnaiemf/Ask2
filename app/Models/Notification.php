<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [//user_id user who notify the other one
        'user_id', 'notified_user_id', 'type', 'screen', 'data', 'read'
    ];

    protected $hidden =[
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function getDataAttribute($data){

        return json_decode($data);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifiedUser()
    {
        return $this->belongsTo(User::class, 'notified_user_id');
    }



}
