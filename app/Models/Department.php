<?php

namespace App\Models;

use Astrotomic\Translatable\Locales;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name', 'parent_id'
    ];

    protected $hidden = [
		'deleted_at',
        'updated_at',
        'created_at'
	];

    public function toArray()
    {

        $lang = app(Locales::class)->current();

        $array['name'] =$this->{'name_'.$lang};

        if ($this->relationLoaded('subdepartments')) {
            $array['subdepartments'] = $this->subdepartments->toArray();
        }

        return $array;
    }


    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function subdepartments()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function providers()
    {
        return $this->hasMany(Provider::class);
    }
}
