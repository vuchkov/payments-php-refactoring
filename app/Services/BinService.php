<?php

namespace App\Services;

class BinService
{
    private $apiUrl;

    public function __construct($apiUrl = 'https://lookup.binlist.net/')
    {
        $this->apiUrl = $apiUrl;
    }

    public function lookup($bin)
    {
        $response = file_get_contents($this->apiUrl . $bin);

        if ($response === false) {
            throw new \Exception('Error fetching BIN data.');
        }

        $data = json_decode($response);

        if (isset($data->country->alpha2)) {
            return $data->country->alpha2; // Return country code
        }

        throw new \Exception('Invalid BIN data received.');
    }
}

