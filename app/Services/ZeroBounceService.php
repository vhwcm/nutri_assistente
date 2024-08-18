<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class ZeroBounceService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('ZEROBOUNCE_API_KEY');
    }

    public function validateEmail($email)
    {
        try {
            $response = $this->client->request('GET', 'https://api.zerobounce.net/v2/validate', [
                'query' => [
                    'api_key' => $this->apiKey,
                    'email' => $email,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            // Handle exceptions accordingly
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
