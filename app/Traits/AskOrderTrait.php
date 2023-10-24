<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait AskOrderTrait
{
    public function makeOrder($data){

        $data = [
            "service_id" => 3,
            "start_address" => "test from address",
            "start_lat" => "31.211147",
            "start_lng" => "29.946613",
            "end_address" => "test to address",
            "end_lat" => "31.202969",
            "end_lng" => "29.960944",
            "notes" => "test item name",
            "paymentMethod" => "cash",
            "cost" => "28",
            "user_wallet" => 0,
            "app_name" => "Ask",
            "customer_profile" => "test.png",
            "name" => "test",
            "rate" => "5"
        ];

        $response = Http::post('https://captainAsk.com/api/auth/third_party_trip', $data);

        if ($response->successful()) {
            // Request was successful
            $responseData = $response->json();

            // Handle the API response data here
        } else {
            // You can use $response->status() to get the HTTP status code
            // You can use $response->body() to get the response body in case of an error
        }
    }
}
