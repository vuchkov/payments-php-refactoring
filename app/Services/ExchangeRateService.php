<?php

namespace App\Services;

class ExchangeRateService
{
    private $apiUrl;

    public function __construct($apiUrl = 'https://api.exchangeratesapi.io/latest')
    {
        $this->apiUrl = $apiUrl;
    }

    public function getRate($currency)
    {
        $response = file_get_contents($this->apiUrl);

        if ($response === false) {
            throw new Exception('Error fetching exchange rates.');
        }

        $data = json_decode($response, true);

        if (isset($data['rates'][$currency])) {
            return $data['rates'][$currency]; // Return exchange rate for the specified currency
        }

        throw new Exception('Invalid currency code or rate not available.');
    }
}
