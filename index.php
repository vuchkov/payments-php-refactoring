<?php

require 'vendor/autoload.php';

use App\Services\BinService;
use App\Services\ExchangeRateService;
use App\TransactionProcessor;

try {
    // Instantiate services
    $binService = new BinService();
    $exchangeRateService = new ExchangeRateService();

    // Create the transaction processor
    $processor = new TransactionProcessor($binService, $exchangeRateService);

    // Specify the input file
    $inputFile = 'input.txt';

    // Process transactions
    $results = $processor->processTransactions($inputFile);

    // Output results
    foreach ($results as $commission) {
        echo $commission . "\n";
    }
} catch (Exception $e) {
    // Handle exceptions gracefully
    echo "Error: " . $e->getMessage() . "\n";
}
