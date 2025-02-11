<?php

class TransactionProcessor
{
    private $binService;
    private $exchangeRateService;

    public function __construct($binService, $exchangeRateService)
    {
        $this->binService = $binService;
        $this->exchangeRateService = $exchangeRateService;
    }

    public function processTransactions($inputFile)
    {
        $results = [];
        foreach (explode("\n", file_get_contents($inputFile)) as $row) {
            if (empty($row)) continue;
            $transaction = json_decode($row, true);
            $commission = $this->calculateCommission($transaction);
            $results[] = $commission;
        }
        return $results;
    }

    private function calculateCommission($transaction)
    {
        $bin = $transaction['bin'];
        $amount = $transaction['amount'];
        $currency = $transaction['currency'];

        $countryCode = $this->binService->lookup($bin);
        $isEu = $this->isEu($countryCode);
        $rate = $this->exchangeRateService->getRate($currency);

        $amountFixed = ($currency == 'EUR' || $rate == 0) ? $amount : $amount / $rate;
        $commission = $amountFixed * ($isEu ? 0.01 : 0.02);

        return ceil($commission * 100) / 100; // Ceiling to the nearest cent
    }

    private function isEu($countryCode)
    {
        $euCountries = [...]; // List of EU country codes
        return in_array($countryCode, $euCountries);
    }
}
