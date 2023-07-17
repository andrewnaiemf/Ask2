<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "rating";
    protected $fillable = [//user_id user who rate the other one
        'user_id', 'rated_user_id', 'rate', 'feedback'
    ];

    protected $hidden =[
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(Provider::class);
    }

    public function ratedUser()
    {
        return $this->belongsTo(Provider::class, 'user_id');
    }
}
