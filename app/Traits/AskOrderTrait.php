<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait AskOrderTrait
{
    public function makeOrder($order){

        $customer = $order->user;
        $provider = $order->provider;
        $provider_address = $provider->user->addresses->first();
        $customerr_address =  $order->address;
        $order_items = self::orderItems($order);

        $data = [
            "start_address" => $provider_address->address,
            "start_lat" => $provider_address->lat,
            "start_lng" => $provider_address->lng,

            "end_address" =>  $customerr_address->address,
            "end_lat" => $customerr_address->lat,
            "end_lng" => $customerr_address->lng,

            "customer_profile" => env('APP_URL') ?? 'http://askshopsa.com'.'/storage/'.$customer->profile,
            "name" => $customer->name,
            "rate" => $customer->rating,

            "notes" => $order_items,

            "cost" => $order->total_amount,

            "service_id" => 3,
            "user_wallet" => 0,
            "paymentMethod" => "cash",
            "app_name" => "Ask"
        ];

        $response = Http::post('https://captainAsk.com/api/auth/third_party_trip', $data);

        if ($response->successful()) {
            // Request was successful
            // $responseData = $response->json();

            // Handle the API response data here
        } else {
            // You can use $response->status() to get the HTTP status code
            // You can use $response->body() to get the response body in case of an error
        }
        return  $response->status();
    }

    public function orderItems($order){
        $productNames = [];

        foreach ($order->orderItems as $orderItem) {
            $productName = $orderItem->product->name;
            $productNames[] = $productName;
        }

       return  implode(', ', $productNames);
    }
}
