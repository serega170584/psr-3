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
        $logType = AbstractLogger::ALERT;
        $logMessage = 'log';
        $this->assertEquals("[{$logType}] {$logMessage}", $this->logger->format($logType, $logMessage), [], false);
    }
}
