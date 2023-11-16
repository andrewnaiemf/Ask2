<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemAddon extends Model
{
    use HasFactory;

     protected $table = 'order_item_addons';

     public $fillable = ['order_item_id', 'addon_id', 'qty'];


     public function orderItem()
     {
         return $this->belongsTo(OrderItem::class);
     }

     public function addon()
     {
         return $this->belongsTo(Addon::class);
     }
}
