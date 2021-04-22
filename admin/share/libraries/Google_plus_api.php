<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'vendor/autoload.php';

/**
 * Facebook v5.x.x
 * 
 * @property Google_Client $google
 */
class Google_plus_api {

    private $google;
    public $applicationName;
    public $clientId;
    public $clientSecret;

    function setApplicationName($applicationName) {
        $this->applicationName = $applicationName;
        return $this;
    }

    function setClientId($clientId) {
        $this->clientId = $clientId;
        return $this;
    }

    function setClientSecret($clientSecret) {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    function __construct($config = array()) {
        $this->initialize($config);
    }

    private function initialize($config = array()) {
        foreach ($config as $key => $val) {
            if (isset($this->$key)) {
                $pass = false;
                switch ($key) {
                    case 'service_url':
                        $pass = true;
                        break;
                    default :
                        $pass = false;
                        break;
                }

                if ($pass) {
                    continue;
                }

                $method = 'set' . ucfirst($key);

                if (method_exists($this, $method)) {
                    $this->$method($val);
                } else {
                    $this->$key = $val;
                }
            }
        }

        return $this;
    }

    private function prepareGoogle() {
        if (!$this->google) {
            $google = new Google_Client();
            $google->setApplicationName($this->applicationName);
            $google->setClientId($this->clientId);
            $google->setClientSecret($this->clientSecret);
            $this->google = $google;
        }

        return $this->google;
    }

    function loginUrl($return_url, $permissions = ['https://www.googleapis.com/auth/userinfo.profile','https://www.googleapis.com/auth/userinfo.email']) {
        $client = $this->prepareGoogle();
        $client->addScope($permissions);
        $client->setRedirectUri($return_url);
        $authUrl = $client->createAuthUrl();

        return $authUrl;
    }

    function getAccessTokenInfo($return_url) {
//        $this->handle_OAuth_URI();

        if (isset($_GET['code'])) {
            $client = $this->prepareGoogle();

            $code = $_GET['code'];
            $client->setRedirectUri($return_url);

            $result = $client->authenticate($code);
            
            if (isset($result['error'])) {
                die($result['error_description']);
            }

//            echo '<pre>', print_r($result['access_token']), '</pre>';
//            exit;
            $google_access_token = $result['access_token'];
            if ($google_access_token) {
                $_SESSION['google_access_token'] = $google_access_token;
            }
        }
    }

    function getAccountInfo() {
        $user = NULL;

        if (isset($_SESSION['google_access_token'])) {
            $client = $this->prepareGoogle();
            $client->setAccessToken($_SESSION['google_access_token']);

            $oauth2 = new Google_Service_Plus($client);

            $user = $oauth2->people->get('me');
        }

        return $user;
    }

    function logout() {
        $client = $this->prepareGoogle();

        unset($_SESSION['google_access_token']);
        $client->revokeToken();
    }

}
