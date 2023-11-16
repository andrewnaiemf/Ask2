<?php

namespace App\Rules;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class ValidateStock implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (request()->input('product_id')) {
            $productId = request()->input('product_id');
        } elseif (request()->input('orderItemId')) {
            $productId = OrderItem::find(request()->input('orderItemId'))->product_id;
        }
        $product = Product::find($productId);

        if (!$product) {
            return false;
        }

        if (in_array(
            $product->provider->subdepartment->name_en,
            [
            'Restaurants',
            'Craft works',
            'Food and sweets',
            'Cafes',
            'Furniture and electrical appliances',
            'Second hand stores',
            'Household supplies',
            'Sweets and nuts',
            'Food and perfume materials',
            'Beauty corner',
            'clothes and shoes',
            'Insulators',
            'Blacksmithing and carpentry',
            'Electricity and plumbing',
            'Tiles and paint'
            ]
        )
        ) {//ignor stock quantity
            return true;
        }


        return $product->stock >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected quantity is not available in stock.';
    }
}
