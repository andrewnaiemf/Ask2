<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'color',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // public function size(){
    //     return $this->belongsTo(Product::class);
    // }
    // public function color(){
    //     return $this->belongsTo(Color::class);
    // }
}
