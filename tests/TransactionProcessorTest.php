<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Services\BinService;
use App\Services\ExchangeRateService;
use App\TransactionProcessor;

class TransactionProcessorTest extends TestCase
{
    public function testCalculateCommission()
    {
        $binServiceMock = $this->createMock(BinService::class);
        $binServiceMock->method('lookup')->willReturn('DE');

        $rateServiceMock = $this->createMock(ExchangeRateService::class);
        $rateServiceMock->method('getRate')->willReturn(1.2); // Example rate

        $processor = new TransactionProcessor($binServiceMock, $rateServiceMock);
        $result = $processor->processTransactions('input.txt');

        $this->assertEquals(expected_value, $result[0]); // Replace with actual expected value
    }
}
