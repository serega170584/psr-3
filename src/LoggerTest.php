<?php

namespace Serega170584\PSR3;

use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{

    /**
     * @var Logger
     */
    private $logger;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logger = new Logger();
    }

    public function testFormat()
    {
        array_map(function ($val) {
            $logType = $val;
            $logMessage = 'log';
            $this->assertEquals("[{$logType}] {$logMessage}" . PHP_EOL, $this->logger->format($logType, $logMessage, [], false));
        }, array_keys(AbstractLogger::LEVELS));
    }
}
