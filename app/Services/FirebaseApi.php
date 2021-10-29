<?php

namespace App\Services;

use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseApi{
    private $firebaseDB;
    private string $fcmKey, $fcmUrl;

    public function __construct(){
        $this->firebaseDB = app('firebase.database');
        $this->fcmKey = 'AAAAtFLsLjU:APA91bE9_rxiOwsn-Trb6sfb9O4lxdlp0JH5VpQTqB_YvcARcR--cZ-hwwBE-Bm5mMoBmPpDUNN2KZQm5qfoBjwv4_5WK8HIu3Lsj9NX6voAH7mU1HmdT0PrJHZeQP3O2185hhCtb8UL';
        $this->fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    }

    public function getRef($ref){
        return $this->firebaseDB->getReference($ref);
    }

    public function sendPushNotification($notifiables, $title = "Title", $body = "Body")
    {
        $deviceTokens = [];

        foreach ($notifiables ?? [] as $notifiable) {
            foreach ($notifiable->column('fcm_token') ?? [] as $token) {
                $deviceTokens[] =  $token;
            }
        }

        $data = [
            "registration_ids" => $deviceTokens,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "icon" => asset('assets/images/logos/group.png')
            ]
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

    public function sendNotification($sender, $notifiables, $title, $body, $route)
    {
        $notificationModel = $this->getRef('notifications');
        foreach ($notifiables ?? [] as $notifiable){
            $notificationModel->push([
                'notifiable_id' => $notifiable->id,
                'user' => [
                    'avatar' => image($sender->avatar),
                    'fullname' => $sender->fullname
                ],
                'message' => $title,
                'content' => $body,
                'url' =>  $route,
                'wasPlayed' => false
            ]);
        }

        // firebase push notifications
        $this->sendPushNotification($notifiables, $title, $body);
    }
}