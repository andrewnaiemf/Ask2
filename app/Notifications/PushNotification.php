<?php

namespace App\Notifications;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Http;


class PushNotification
{
    public function send($ids ,$message, $notification_data = null)
    {
        $screen = '';
        switch ($message) {
            case 'rating':
                $screen  = 'rating';
                $message = 'Someone rate you';
            break;

            case 'booking':
                $screen  = 'booking';
                $message = 'You have new booking';
            break;

            default:
                $screen  = 'home_screen';
            break;
        }

        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = env('FCM_KEY') ?? '';
        $devs=[];

        $devices = User::whereIn('id',$ids)->pluck('device_token');
        foreach ($devices as $tokens) {
            if( $tokens){
                foreach ($tokens as $token){
                    array_push($devs, $token);
                }
            }
        }

        $data = [
            "registration_ids" =>$devs,
            "notification" => [
                "body" => $message,
                "title" => 'Captain ask',
                "sound" => "notify.mp3",
            ],
            "data" => [
                'screen' => $screen,
                'notification_data' => json_encode($notification_data)
            ]
        ];

        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        // FCM response
        return json_decode($result);
    }


    public static function create($sender_id, $resciever_id, $data, $type){

        Notification::create([
            'user_id' =>  $sender_id,
            'notified_user_id' =>  $resciever_id,
            'type' =>  $type,
            'screen' =>  $type,
            'data' =>$data
        ]);

        PushNotification::send([$resciever_id], $type, $data);
    }
}
