<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseApi{
    private $firebaseDB;
    private string $fcmKey, $fcmUrl;

    public function __construct(){
        $this->firebaseDB = app('firebase.database');
        $this->fcmKey = config('firebase.projects.app.credentials.fcm_token');
        $this->fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    }

    public function getRef($ref){
        return $this->firebaseDB->getReference($ref);
    }

    public function sendPushNotification($receivers, $url, $title = "Title", $body = "Body")
    {
        // if(app()->environment('local')) return;

        $deviceTokens = [];

        foreach ($receivers ?? [] as $receiver) {
            foreach ($receiver->deviceFcmTokens() ?? [] as $token) {
                $deviceTokens[] =  $token;
            }
        }

        $data = [
            "registration_ids" => $deviceTokens,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "icon" => asset('assets/images/logos/group.png'),
                'click_action' => $url
            ],
        ];

        $RESPONSE = json_encode($data);

        $headers = [
            'Authorization:key=' . $this->fcmKey,
            'Content-Type:application/json',
        ];

        // CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $RESPONSE);

        $output = curl_exec($ch);
        if ($output === FALSE) {
            die('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);
    }

    public function sendNotification($creator, $receivers, $title, $body, $url)
    {
        // if(app()->environment('local')) return;

        $notificationModel = $this->getRef('notifications');
        foreach ($receivers ?? [] as $receiver){
            $notificationModel->push([
                'receiver_id' => $receiver->id,
                'user' => [
                    'avatar' => image($creator->avatar),
                    'fullname' => $creator->fullname
                ],
                'message' => $title,
                'content' => $body,
                'url' =>  $url,
                'wasPlayed' => false
            ]);
        }
    }
}