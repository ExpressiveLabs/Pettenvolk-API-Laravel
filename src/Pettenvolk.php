<?php

namespace mainstreamct\pettenvolk-api;

class Pettenvolk {
    public function auth($email, $password) {
    	$app = config('pettenvolk.api_key');

        $client = new \Guzzle\Service\Client('https://chameleon.pettenvolk.com/');
        $response = $client->post("auth?email=$email&pw=$password&app_key=$app")->send();

        return $response;
    }

    public function user($id, $api_token) {
    	$app = config('pettenvolk.api_key');
    	
        $client = new \Guzzle\Service\Client('https://chameleon.pettenvolk.com/');
        if($api_token) {
        	$response = $client->get("fetchUser?user_id=$id&api_token=$api_token")->send();
        }
        else {
        	$response = $client->get("fetchUser?user_id=$id&api_token=".Auth::user()->pettenvolk_api_token)->send();
        }

        return $response;
    }
}