<?php

namespace League\OAuth2\Client\Provider;

use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Token\AccessToken;



/*============================================ QSCID Details=========================
Authorization URL:
When you want to authenticate your users, you must send them to the following URL:
https://qscid.cicnode.com/oauth/authorize?client_id=[clientId]&redirect_uri=[redirectUri]&response_type=code

For your clientId and redirectUri for each application see the details below.

Access Token URL:
Your library will need to POST the "code" provided at [redirectUri] to the following URL:
 https://qscid.cicnode.com/oauth/token

User info API URL:
To fetch the user details for a particular user, your app will need to hit the API at:
http://localhost:3000/api/v1/users/me
You will need to pass the token in an HTTP header called "Authorization" with a value of "Bearer: [token]", where [token] is the token you got from the Access Token URL.

Application Access Details:
Here are the access details you'll need for each of the applications to use the system. For production, we'll be more security-conscious about sending these over email/Basecamp, but for now I think this is OK. Note I've arbitrarily chosen the URI "/oauth/callback/" for each of these, but if you need to change that, just say the word:
QSC LMS DEV
Client Id:    906dfae2573ed8c695b7152dffb0e2636e64f6e27f8a1c803f8ba5f356f9aec0
==================================================================================
*/

class Qscid extends AbstractProvider
{
    public $scopes = [];
    public $scopeSeparator = ' ';
    public $responseType = 'json';
    public $authorizationHeader = 'Bearer';
    /*public $fields = [
        'id', 'email-address',
    ];*/

    public function urlAuthorize()
    {
        //return 'https://qscid.cicnode.com/oauth/authorize';
        //return 'https://qscid.ciclabs.com/oauth/authorize';
        //return 'https://iddev.qsc.com/oauth/authorize';
        return 'https://id.qsc.com/oauth/authorize';
    }

    public function urlAccessToken()
    {
        //return 'http://qscid.cicnode.com/oauth/token';
        //return 'https://qscid.ciclabs.com/oauth/token';
        //return 'https://iddev.qsc.com/oauth/token';
        return 'https://id.qsc.com/oauth/token';
    }

    public function urlUserDetails(AccessToken $token)
    {

 // echo $token;
 // die;

        //return 'http://qscid.cicnode.com/api/v1/users/me?access_token='.$token;
        //return 'https://qscid.ciclabs.com/api/v1/users/me?access_token='.$token;
        //return 'https://iddev.qsc.com/api/v1/users/me?access_token='.$token;
        return 'https://id.qsc.com/api/v1/users/me?access_token='.$token;

    }

    public function userDetails($response, AccessToken $token)
    {


        $providerUserId = (isset($response->data->id)) ? $response->data->id : 0;
        $user_type = (isset($response->data->type)) ? $response->data->type : "";
        $responseData = (array)$response->data->attributes;

        // if($responseData['email'] == 'sameer.chourasia@beyondkey.com'){
        //     echo "<pre>";
        //     echo $token;
        //     print_r($response);die;
        //     //echo json_encode($response);
        //     //die;
        // }

        $first_name = (isset($responseData['first-name'])) ? $responseData['first-name'] : "";
        $last_name = (isset($responseData['last-name'])) ? $responseData['last-name'] : "";
        $email = (isset($responseData['email'])) ? $responseData['email'] : "";
        $email_opt_in = (isset($responseData['email-opt-in'])) ? $responseData['email-opt-in'] : 0; 
        $phone = (isset($responseData['phone'])) ? $responseData['phone'] : "";
        $salesforce_identifier = (isset($responseData['salesforce-identifier'])) ? $responseData['salesforce-identifier'] : 0;
        $company_email = (isset($responseData['company-email'])) ? $responseData['company-email'] : "";
        $company_name = (isset($responseData['company-name'])) ? $responseData['company-name'] : "";
        $country_code = (isset($responseData['country-code'])) ? $responseData['country-code'] : "";


        $relationshipsData = (array)$response->data->relationships;
        
        if($response->included[0]->type == 'addresses'){
                $addressAttributes = (array)$response->included[0]->attributes;

                $address_1 = (isset($addressAttributes['address-1'])) ? $addressAttributes['address-1'] : "";
                $address_2 = (isset($addressAttributes['address-2'])) ? $addressAttributes['address-2'] : "";
                $city = (isset($addressAttributes['city'])) ? $addressAttributes['city'] : "";
                $state = (isset($addressAttributes['state'])) ? $addressAttributes['state'] : "";
                $postal_code = (isset($addressAttributes['postal-code'])) ? $addressAttributes['postal-code'] : "";
                //$country = (isset($addressAttributes['country'])) ? $addressAttributes['country'] : "";
        }else{
                $address_1 = "";
                $address_2 = "";
                $city = "";
                $state = "";
                $postal_code = "";
        }

        //$user = new User();
        // $user->exchangeArray([
        //     'user_id' => $user_id,
        //     'first_name' => $first_name,
        //     'last_name' => $last_name,
        //     'email' => $email,
        //     'email_opt_in' => $email_opt_in,
        //     'phone' => $phone,
        //     'salesforce_identifier' => $salesforce_identifier,
        //     'company_email' => $company_email,

        //     'address_1' => $address_1,
        //     'address_2' => $address_2,
        //     'city' => $city,
        //     'state' => $state,
        //     'postal_code' => $postal_code,
        //     'country' => $country,
        // ]);


        $userData = Array(
            'providerUserId' => $providerUserId,
            //'user_type' => $user_type,
            'firstname' => $first_name,
            'lastname' => $last_name,
            'email' => $email,
            'phone1' => $phone,
            //'emailOptIn' => $email_opt_in,
            'sfdcuniqueid' => $salesforce_identifier,
            //'companyemail' => $company_email,
            'institution' => $company_name,
            'address' => $address_1,
            'address_two' => $address_2,
            'city' => $city,
            'state' => $state,
            'zip' => $postal_code,
            'country' => $country_code,
        );


        // if($responseData['email'] == 'sameer.chourasia@beyondkey.com'){
        //     echo "<pre>";
        //     echo json_encode($responseData);
        //     print_r($userData);
        //     die;
        // }

        return $userData;
    }

    public function userUid($response, AccessToken $token)
    {
        return $response->id;
    }

    public function userEmail($response, AccessToken $token)
    {
        return isset($response->emailAddress) && $response->emailAddress
            ? $response->emailAddress
            : null;
    }

    public function userScreenName($response, AccessToken $token)
    {
        return [$response->firstName, $response->lastName];
    }


}
