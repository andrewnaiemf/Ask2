<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemAttribute extends Model
{
    use HasFactory;

    protected $table = 'order_itmes_attributes';

    public $fillable = ['order_item_id', 'color_id', 'product_attribute_id'];

    public function toArray()
    {
        // $attribute = parent::toArray();
        $attribute['size'] =  $this->attribute ? $this->attribute->size : '';
        $attribute['color'] = ['value' => $this->color->value,  'name' =>  $this->color->name];
        $attribute = array_merge(['id' => $this->id], $attribute);
        return $attribute;
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function color()
    {
        return $this->hasOne(Color::class, 'id');
    }

    public function size()
    {
        return $this->attribute()->attribute();
    }

    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }


}
