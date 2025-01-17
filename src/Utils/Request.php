<?php

namespace LasseRafn\Dinero\Utils;

use GuzzleHttp\Client;

class Request
{
    public $curl;

    protected $baseUri = 'https://api.dinero.dk/';
    protected $authUri = 'https://authz.dinero.dk/dineroapi/oauth/token';

    public function __construct($clientId = '', $clientSecret = '', $token = null, $org = null, $clientConfig = [], $apiVersion = 'v1')
    {
        $encodedClientIdAndSecret = base64_encode("{$clientId}:{$clientSecret}");

        $headers = [];

        if ($token !== null) {
            $headers['Authorization'] = "Bearer {$token}";
            $headers['Content-Type'] = 'application/json; charset=utf8';
        } else {
            $headers['Authorization'] = "Basic {$encodedClientIdAndSecret}";
            $headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf8';
        }

        $this->curl = new Client(array_merge_recursive([
            'base_uri' => $this->baseUri.$apiVersion.($org !== null ? "/{$org}/" : ''),
            'headers'  => $headers,
        ], $clientConfig));
    }

    /**
     * Return a string with the oAuth url.
     *
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->authUri;
    }
}
