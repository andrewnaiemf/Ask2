<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentProvider extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'document_provider';
    protected $fillable = [
        'provider_id', 'name', 'path'
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

}
