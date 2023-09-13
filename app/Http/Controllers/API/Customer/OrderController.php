<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Rules\ValidateStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        if ( $validation) {
            return $validation;
        }

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'provider_id' => $request->provider_id,
            'status' => "Cart",
            'address_id' => Null,
            'sub_total_price' => Null,
            'coupon_amount' => Null,
            'total_amount' => Null,
            'payment_status' => 'Pending',
            'shipping_status' => Null,
            'shipping_method' =>Null,
        ]);


        $product = Product::find($request->product_id);
        $orderItem = new OrderItem([
            'product_id' => $product->id,
            'qty' => $request->qty,
            'unit_price' =>  $product->price
        ]);
        $order->orderItems()->save($orderItem);

        $price = $product->price * $request->qty;
        $order->update([
            'total_amount' => $price,
            'sub_total_price' => $price
        ]);

        $order->load('orderItems');

        return $this->returnData($order);

    }

    public function validateCartData($request){

        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:providers,id',
            // 'address_id' => 'required|exists:addresses,id',
            // 'sub_total_price' => 'required|numeric|min:0',
            // 'coupon_amount' => 'numeric|min:0',
            // 'total_amount' => 'required|numeric|min:0',
            // 'shipping_method' => 'required|in:CaptainAsk,OurDelivery',
            'product_id' => 'required|exists:products,id',
            'qty' => [
                'required',
                'integer',
                'min:1',
                new ValidateStock(), // Use the custom validation rule here
            ],
            // 'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
    }

    public function updateCart(Request $request, $id){

        $order = Order::findOrFail($id);

        if ($order->status !== 'Cart') {
            return $this->returnError('api.Cannot_update-order.');
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'qty' => [
                'required',
                'integer',
                new ValidateStock(),
            ],
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }

        $orderItem = $order->orderItems()->where('product_id', $request->product_id)->first();

        if ($request->qty !== 0) {
            if ($orderItem) {

                $orderItem->qty = $request->input('qty');
                $orderItem->save();

            }else{

                $product = Product::find($request->product_id);
                $newOrderItem = new OrderItem([
                    'product_id' => $product->id,
                    'qty' => $request->qty,
                    'unit_price' =>  $product->price
                ]);

                $order->orderItems()->save($newOrderItem);
            }
        }else{
            $orderItem->delete();
        }

        // Recalculate the sub_total_price for the order based on updated order items
        $order->sub_total_price = $order->orderItems->sum(function ($item) {
            return $item->qty * $item->unit_price;
        });

        // Recalculate the total_amount
        $order->total_amount = $order->sub_total_price;

        $order->save();

        return $this->returnSuccessMessage('api.cartUpdatedSuccessfully');
    }

    public function showCart($id){

        $order = Order::with('orderItems')->findOrFail($id);

        if ($order->status !== 'Cart') {
            return $this->returnError('api.Cannot_update-order.');
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
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
