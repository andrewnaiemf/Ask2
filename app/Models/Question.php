<?php

namespace App\Models;

use Astrotomic\Translatable\Locales;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;



    protected $hidden =[
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function toArray()
    {

        $lang = app(Locales::class)->current();

        $array['question'] =$this->{'question_'.$lang};
        $array['answer'] =$this->{'answer_'.$lang};

        return $array;
    }


}
