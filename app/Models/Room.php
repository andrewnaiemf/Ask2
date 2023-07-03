<?php

namespace App\Models;

use Astrotomic\Translatable\Locales;
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


    //بعدين ابقا اعملها بال ترانس او اتنين اندر سكول بحيث تكون ترجمة بالتلات لغات
    public function getOutdoorAttribute($value)
    {
        $lang = app(Locales::class)->current();
        if ($lang == 'ar') {
            switch ($value) {
                case 'Balcony':
                    $translate_name = 'بلكونة';
                    break;
                case 'Head':
                    $translate_name = 'تراس';
                    break;
                default:
                    $translate_name = 'اطلالة';
                    break;
            }
            return $translate_name;
        }

        return $value;
    }


    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function beds()
    {
        return $this->belongsToMany(Bed::class, 'bed_room')->withTimestamps();
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
