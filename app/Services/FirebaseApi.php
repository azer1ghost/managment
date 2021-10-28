<?php

namespace App\Services;

class FirebaseApi{
    private $firebaseDB;

    public function __construct(){
        $this->firebaseDB = app('firebase.database');
    }

    public function getRef($ref){
        return $this->firebaseDB->getReference($ref);
    }
}