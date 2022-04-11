<?php

namespace App\Services\V1;

use App\Models\Farm;
use Google_Client;

class FarmGoogleService
{

    public function __construct()
    {
    }

    /**
     * @param $farmGoogle
     * @return Google_Client
     */
    public function createGoogleObject($farmGoogle){
        //Create and Request to access Google API
        $client = new Google_Client();
        $client->setApplicationName("Google OAuth Login With PHP");
        $client->setClientId($farmGoogle->client_id);
        $client->setClientSecret($farmGoogle->client_secret);
        $client->setRedirectUri("http://127.0.0.1:8000/farm_google/auth_redirect");
        $client->setScopes($farmGoogle->scope);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        return $client;
    }
    /**
     * @param $data_to_update
     * @param $tokens
     * @return mixed
     * This will
     */
    public function parseTokenFetched($data_to_update, $tokens){
        $data_to_update = array_merge($data_to_update, [
            "access_token"=>$tokens["access_token"],
            "token_type"=>$tokens["token_type"],
            "expires_in"=>$tokens["expires_in"]
        ]);
        if(isset($tokens["scope"]) && $tokens["scope"] != ""){
            $data_to_update["scope"] = $tokens["scope"];
        }
        if(isset($tokens["refresh_token"])){
            $data_to_update["refresh_token"] = $tokens["refresh_token"];
        }
        if(isset($tokens["id_token"])){
            $data_to_update["id_token"] = $tokens["id_token"];
        }
        return $data_to_update;
    }

    /**
     * @param $client
     * @param $farmGoogle
     * @param $id
     * @return mixed
     */
    public function getAuthURL($client, $farmGoogle, $id){
        $client->setState(base64_encode($farmGoogle->organisation_id.":".$id));
        return $client->createAuthUrl();
    }

    public function fetchTokenFromCode($client, $farmGoogle, $code, $data_to_update){
        $farmGoogle->update(['code' => $code]);
        $tokens = $client->fetchAccessTokenWithAuthCode($code);
        $data_to_update = $this->parseTokenFetched($data_to_update, $tokens);
        if(count($data_to_update) > 0){
            $farmGoogle->update($data_to_update);
        }
        $client->setAccessToken($tokens);
        return $client;
    }

    public function fetchTokenFromRefreshToken($client, $farmGoogle, $data_to_update){
        $tokens = ["access_token"=>$farmGoogle->access_token, "refresh_token"=>$farmGoogle->refresh_token,
            "token_type"=>$farmGoogle->token_type, "scope"=>$farmGoogle->scope, "expires_in"=>$farmGoogle->expires_in];
        $client->setAccessToken($tokens);
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $tokens = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $data_to_update = $this->parseTokenFetched($data_to_update, $tokens);
            $client->setAccessToken($tokens);
            if(count($data_to_update) > 0){
                $farmGoogle->update($data_to_update);
            }
        }
        return $client;
    }

    public function getFirstActiveFarmGoogle(Farm $farm){
        return $farm->google()->first();
    }

    public function getMapLocationUsingAddress($api_key, $address){
//        $address = "Kathmandu, Nepal";
        $url = "https://maps.google.com/maps/api/geocode/json?key=".$api_key."&address=".urlencode($address);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        $status = data_get($response, 'status');
        if($status == "OK"){
            $response = data_get($response, 'results');
            if($response && count($response) > 0){
                $response = data_get($response[0], 'geometry.location');
            }
        }
        if(!isset($response['lat']) && !isset($response['lng'])){
            $response = ['lat' => null, 'lng' => null];
        }
        return $response;
    }

}
