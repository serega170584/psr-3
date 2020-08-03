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

            $placeholderLogMessage = "{$logMessage} {test} {scalar_int} {scalar_float} {scalar_string} {scalar_boolean} {scalar_boolean_false} {object} {simple_object}";
            $logMessage .= '  1 1.01 123 1  123 [object stdClass]';
            $handle = fopen(__DIR__ . DIRECTORY_SEPARATOR . 'StdObject.php', 'r');
            $context = [
                'test' => null,
                'scalar_int' => 1,
                'scalar_float' => 1.01,
                'scalar_string' => '123',
                'scalar_boolean' => true,
                'scalar_boolean_false' => false,
                'object' => new StdObject(),
                'simple_object' => new \stdClass()
//                'date_time' => (new \DateTime())->format(\DateTime::RFC3339),
//                'resource'=> $handle
            ];
            $this->assertEquals("[{$logType}] {$logMessage}" . PHP_EOL, $this->logger->format($logType, $placeholderLogMessage, $context, false));
        }, array_keys(AbstractLogger::LEVELS));
    }
}
