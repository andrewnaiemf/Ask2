<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderOffering extends Model
{
    use HasFactory;

    protected $table = 'provider_offerings';

    public $fillable = ['provider_id','delivery_time', 'coupon_name', 'coupon_value', 'delivery_fees'];

    protected $hidden = [
		'created_at',
        'updated_at',
	];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
}
