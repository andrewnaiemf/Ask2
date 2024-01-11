<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\OrderItemAttribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Provider;
use App\Models\ProviderOffering;
use App\Models\User;
use App\Rules\ValidateStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Notifications\PushNotification;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->header('per_page', 10);

        $orders = User::find(auth()->user()->id)->orders()->where(['type' => 'Order'])
        ->when($request->status == 'New', function ($query) {
            return $query->whereIn('status', ['Accepted','Pending']);
        })
        ->unless($request->status == 'New', function ($query) {
            return $query->whereNotIn('status', ['Accepted','Pending']);
        })
        ->with(['orderItems.product',
            'orderItems.attribute.color',
            'orderItems.addons.addon',
            'provider.user',
            'address
         '])
        ->orderBy('updated_at', 'desc')
        ->simplePaginate($perPage);

        return $this->returnData($orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function cart(Request $request)
    {

        $validation = $this->validateCartData($request);

        if ($validation) {
            return $validation;
        }

        $order = Order::where(['user_id' => auth()->user()->id,'type' => 'Cart'])->first();

        if ($order) {
            return $this->returnError('You already have cart');
        }

        DB::beginTransaction();

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'provider_id' => $request->provider_id,
            'type' => "Cart",
            'address_id' => null,
            'sub_total_price' => null,
            'coupon_amount' => null,
            'total_amount' => null,
            'payment_status' => 'Pending',
            'shipping_status' => null,
            'shipping_method' =>null,
        ]);


        $product = Product::find($request->product_id);
        $orderItem = new OrderItem([
            'product_id' => $product->id,
            'qty' => $request->qty,
            'unit_price' =>  $product->price
        ]);
        $order->orderItems()->save($orderItem);



        if ($request->has('addons')) {
            $addonPrice = $this->attachAddon($request, $orderItem);
        }

        if ($request->has('color_id') || $request->has('size')) {
            $is_attached = $this->attachAttributes($request, $order, $orderItem);

            if (isset($is_attached) && !$is_attached) {
                DB::rollBack();
                return $this->returnError('color_id is invalid');
            }
        }



        $price = $product->price * $request->qty +  $addonPrice;
        $order->update([
            'total_amount' => $price,
            'sub_total_price' => $price
        ]);

        $order->load('orderItems.addons.addon', 'orderItems.attribute.color');
        DB::commit();
        return $this->returnData($order);

    }

    public function attachAttributes($request,  $order, $orderItem)
    {


        if($request->product_id) {
            $orderItem = $order->orderItems()->where('product_id', $request->product_id)->first();
        }

        $order_itmes_attribute_id = ProductAttribute::where('product_id', $request->product_id)->first()->id;
        $color_ids = Product::find($request->product_id)->colors->pluck('id')->toArray();

        if(isset($color_ids) && !in_array($request->color_id ,$color_ids)){
            return true;
        }


        $attribute = new OrderItemAttribute([
            'product_attribute_id' => $order_itmes_attribute_id,
            'color_id' => $request->color_id
        ]);

        $orderItem->attribute()->save($attribute);

    }

    // public function attachSize($request,  $order, $orderItem)
    // {

    //     if ($request->orderItemIs) {
    //         $orderItem = $order->orderItems()->where('id', $request->orderItemId)->first();
    //     }

    //     if($request->product_id) {
    //         $orderItem = $order->orderItems()->where('product_id', $request->product_id)->first();
    //     }
    //     $order_itmes_attribute_id = ProductAttribute::where('product_id', $request->product_id)->first()->id;

    //     if (isset($orderItem->attribute) && $orderItem->attribute->size) {
    //         $orderItem->attribute->update([
    //             'product_attribute_id' => $order_itmes_attribute_id,
    //         ]);
    //     } else {
    //         $attribute = new OrderItemAttribute([
    //             'product_attribute_id' => $order_itmes_attribute_id,
    //         ]);

    //         $orderItem->attribute()->save($attribute);
    //     }
    // }

    public function attachAddon($request, $orderItem)
    {

        $addonPrice = 0;

        foreach ($request->addons as $addon) {
            $productAddon = Addon::find($addon['id']);
            $existingOrderItemAddon = $orderItem->addons()->where('addon_id', $productAddon->id)->first();


            if ($existingOrderItemAddon) {
                // Update the quantity if it's not zero, else delete the existing OrderItemAddon
                if ($addon['qty'] !== 0) {
                    $existingOrderItemAddon->qty = $addon['qty'];
                    $existingOrderItemAddon->save();
                    $addonPrice += $productAddon->price * $addon['qty'];
                } else {
                    $existingOrderItemAddon->delete();
                }
            } else {
                $orderItemAddon = new OrderItemAddon([
                    'order_item_id' => $orderItem->id,
                    'addon_id' => $productAddon->id,
                    'qty' => $request->qty
                ]);
                $orderItem->addons()->save($orderItemAddon);
                $addonPrice += $productAddon->price * $request->qty;
            }
        }

        return  $addonPrice;

    }

    public function validateCartData($request)
    {

        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:providers,id',
            'product_id' => 'required|exists:products,id',
            'qty' => [
                'required',
                'integer',
                'min:1',
                new ValidateStock(), // Use the custom validation rule here
            ],
            'addons' => 'nullable|array',
            'addons.*.id' => 'nullable|integer|exists:addons,id',
            'size' => 'nullable',
            'color_id' =>'nullable|integer|exists:colors,id'
        ]);

        if ($validator->fails()) {
            return $this->returnError($validator->errors()->all());
        }
    }

    public function updateCart(Request $request)
    {

        $order = Order::where(['user_id' => auth()->user()->id,'type' => 'Cart'])->first();

        $validator = Validator::make($request->all(), [
            'product_id' => 'nullable|exists:products,id',
            'qty' => [
                'nullable',
                'integer',
                new ValidateStock(),
            ],
            'shipping_method' => 'nullable|In:Pickup,OurDelivery',
            'address_id' => 'nullable|exists:addresses,id',
            'type' => 'nullable|In:Order',
            'addons' => 'nullable|array',
            'addons.*.id' => 'nullable|integer|exists:addons,id'
        ]);

        if ($validator->fails()) {
            return $this->returnError($validator->errors()->all());
        }

        if (isset($request->qty)) {
            $cart_updated =  $this->updateQty($request, $order);
            if(!$cart_updated) {
                return $this->returnError('api.there is not item with this id ');
            }
        }

        if ($request->coupon) {
            $validCoupon = $this->applyCoupon($request->coupon, $order);
            if (!$validCoupon) {
                return $this->returnError('api.InvalidCoupon');
            }
        }

        if ($request->shipping_method) {
            $this->updateShipping($request, $order);
        }

        if ($request->address_id) {
            $order->address_id = $request->address_id;
        }

        // Recalculate the total_amount
        $order->total_amount = $order->sub_total_price - $order->coupon_amount;
        if (isset($request->shipping_method) && $request->shipping_method == 'OurDelivery') {
            $order->total_amount += $order->delivery_fees;
        }

        $order->save();

        if ($request->type) {
            $checkStock = $this->chechStock($order);
            if (!$checkStock) {
                return $this->returnSuccessMessage('api.someProductIsNotAvailableNow');
            }
            $order->update(['type' => $request->type, 'status' => 'Accepted']);
            $this->decreaseStock($order);
            PushNotification::create($order->user_id ,$order->provider->user_id ,$order ,'new_order');

            return $this->returnSuccessMessage('api.orderCreatedSuccessfully');
        }

        return $this->returnSuccessMessage('api.cartUpdatedSuccessfully');
    }

    public function chechStock($order){
        $orderItems = $order->orderItems;

        foreach ($orderItems as $item) {
            $itemQty = $item->qty;
            $product = $item->product;

            if ($product && $product->stock < $itemQty) {
                return false; // Return false if stock is less than item qty
            }
        }

        return true; // Return true if stock is sufficient for all items
    }

    public function updateShipping($request, $order)
    {

        // if ($request->shipping_method == 'OurDelivery') {
        //     $offer = ProviderOffering::where('provider_id', $order->provider_id)->first();

        //     $delivey_fees = $offer->delivey_fees;
        // }
        $order->update([
            'shipping_method' => $request->shipping_method
        ]);
    }

    public function applyCoupon($coupon, $order)
    {
        $offer = ProviderOffering::where('provider_id', $order->provider_id)->first();

        if ($offer && $offer->coupon_name == $coupon) {
            $total_amount = $order->total_amount - $offer->coupon_value;
            if (!$order->coupon_amount) {
                $order->update([
                    'coupon_amount' => $offer->coupon_value,
                ]);
                return true;
            }

        } else {
            return false;
        }

    }


    public function updateQty($request, $order)
    {
        if ($request->orderItemId) {
            $orderItem = $order->orderItems()->where('id', $request->orderItemId)->first();
        } else {
            return false;
        }
        if($request->product_id) {
            $orderItem = $order->orderItems()->where('product_id', $request->product_id)->first();
        }

        if ($request->qty !== 0) {
            if ($orderItem && !$request->is_add_again) {

                $orderItem->qty = $request->input('qty');
                $orderItem->save();

            } else {

                $product = Product::find($request->product_id);
                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => $request->qty,
                    'unit_price' =>  $product->price
                ]);
                $orderItem->save();
            }
            if($request->addons) {
                $this->attachAddon($request, $orderItem);
            }

            $this->updateSubTotalPrice($order);
        } else {
            if (isset($orderItem)) {
                $orderItem->delete();
            }
        }

        if (count($order->orderItems) == 0) {
            $order->delete();
        }
    }

    public function updateSubTotalPrice($order)
    {
        // Calculate the subtotal for the ordered items
        $subTotalItemsPrice = $order->orderItems->sum(function ($item) {
            return $item->qty * $item->unit_price;
        });

        // Calculate the total addon price for all order items
        $addonPrice = $order->orderItems->flatMap(function ($item) {
            return $item->addons;
        })->sum(function ($addon) {
            return $addon->qty * $addon->addon->price;
        });

        // Update the sub_total_price of the order
        $order->sub_total_price = $subTotalItemsPrice + $addonPrice;
        $order->save();
    }


    public function showCart()
    {

        $order = Order::where(['user_id' => auth()->user()->id,'type' => 'Cart'])
            ->with(
                ['orderItems.product',
                'orderItems.attribute.color',
                'orderItems.addons.addon'
            ])->first();
            // $order->load('orderItems.attribute.size');
        if(isset($order->orderItems) && count($order->orderItems) == 0) {
            return $this->returnData(null);
        }
        return $this->returnData($order);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::where(['id' => $id,'type' => 'Order'])
        ->with([
            'user',
            'provider',
            'address',
            'orderItems.attribute.color',
            'orderItems.addons.addon'
            ])->first();

        return $this->returnData($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteCart()
    {
        $order = Order::where(['user_id' => auth()->user()->id, 'type' => 'Cart'])->with('orderItems.product')->first();

        if ($order) {
            foreach ($order->orderItems as $item) {
                $item->delete();
            }
        }

        $order->delete();
        return $this->returnSuccessMessage( __('api.cartDeletedSuccessfully'));
    }
}
