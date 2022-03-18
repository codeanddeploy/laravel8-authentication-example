<?php

namespace App\Services\TwoFactor;

class Authy {

	/**
     * @var \Authy\AuthyApi
     */
    private $api;

    public function __construct()
    {
        $this->api = new \Authy\AuthyApi(config('services.authy.key'));
    }

    /**
     * @param $email
     * @param $phoneNumber
     * @param $countryCode
     * @return int
     * @throws \Exception
     */
    function register($email, $phoneNumber, $countryCode)
    {
        $user = $this->api->registerUser($email, $phoneNumber, $countryCode);

        return $user;
    }

    /**
     * @param $authyId
     * @return bool
     * @throws \Exception
     */
    public function sendToken($authyId)
    {
        $response = $this->api->requestSms($authyId);
        
        return $response;
    }

    /**
     * @param $authyId
     * @param $token
     * @return bool
     * @throws \Exception Nothing will be thrown here
     */
    public function verifyToken($authyId, $token)
    {
        $response = $this->api->verifyToken($authyId, $token);

        return $response;
    }

    /**
     * @param $authyId
     * @return \Authy\value status
     * @throws \Exception if request to api fails
     */
    public function verifyUserStatus($authyId) {
        $response = $this->api->userStatus($authyId);

        return $response;
    }

}