# Task: Refactor PHP Code and Write Unit Tests

## Objective

Refactor the provided PHP code to improve readability, maintainability, and extendibility. Additionally, write unit tests to ensure the functionality remains consistent and reliable.

## Code Refactoring Plan

### 1. Analyze the Existing Code

The original code reads transaction data from a file, processes it to calculate commissions based on the card's BIN number and currency, and outputs the results.

### 2. Key Improvements

- **Use of Classes**: Implement object-oriented principles to encapsulate functionality.
- **Error Handling**: Replace `die()` with exceptions for better error management.
- **Dependency Injection**: Allow for easier substitution of external services (e.g., BIN lookup, currency rates).
- **Input Validation**: Ensure input data is validated before processing.
- **Commission Ceiling**: Implement a ceiling function to round up commission amounts to the nearest cent.

### 3. Refactored Code Structure

```php
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
```
