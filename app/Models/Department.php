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

    $array['id'] = $this['id'];
    $array['name'] = $this->{'name_'.$lang};
    if ($this->relationLoaded('subdepartments')) {
        $array['subdepartments'] = $this->subdepartments->map(function ($subdepartment) {
            // Load the providers relationship if it's not already loaded
            if (!$subdepartment->relationLoaded('providers')) {
                $subdepartment->load('providers');
            }

            $subdepartmentProviders = $subdepartment->providers->toArray();
            // dd( $subdepartmentProviders);
            return array_merge($subdepartment->toArray(), ['providers' => $subdepartmentProviders]);
        })->toArray();
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
        return $this->hasManyThrough(
            Provider::class,
            Department::class,
            'id', // Foreign key on the departments table
            'subdepartment_id', // Foreign key on the providers table
            'id', // Local key on the current model (departments table)
            'id' // Local key on the intermediate model (providers table)
        )->where('status', 'Accepted');
    }
}
