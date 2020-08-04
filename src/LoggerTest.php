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
        $this->logger = new Logger('php://output');
    }

    public function testFormat()
    {
        array_map(function ($val) {
            $logType = $val;
            $logMessage = 'log';
            $this->assertEquals("[{$logType}] {$logMessage}" . PHP_EOL, $this->logger->format($logType, $logMessage, [], false));

            $dateTime = new \DateTime();
            $dateTimeStr = $dateTime->format(\DateTime::RFC3339);
            $placeholderLogMessage = "{$logMessage} {test} {scalar_int} {scalar_float} {scalar_string} {scalar_boolean} {scalar_boolean_false} {object} {simple_object} {date_time} {resource}";
            $handle = fopen(__DIR__ . DIRECTORY_SEPARATOR . 'StdObject.php', 'r');
            $logMessage .= "  1 1.01 123 1  123 [object stdClass] {$dateTimeStr} [resource]";
            $logMessage = "[{$logType}] {$logMessage}" . PHP_EOL;
            $context = [
                'test' => null,
                'scalar_int' => 1,
                'scalar_float' => 1.01,
                'scalar_string' => '123',
                'scalar_boolean' => true,
                'scalar_boolean_false' => false,
                'object' => new StdObject(),
                'simple_object' => new \stdClass(),
                'date_time' => $dateTime,
                'resource' => $handle
            ];
            $this->assertEquals($logMessage, $this->logger->format($logType, $placeholderLogMessage, $context, false));
            $logMessage = "{$dateTimeStr} {$logMessage}";
            $this->assertEquals($logMessage, $this->logger->format($logType, $placeholderLogMessage, $context));
        }, array_keys(AbstractLogger::LEVELS));
    }

    public function testLogLevelSpecificMethods()
    {
        array_map(function ($val) {
            ob_start();
            /**
             * @uses \Serega170584\PSR3\AbstractLogger::emergency()
             * @uses \Serega170584\PSR3\AbstractLogger::alert()
             * @uses \Serega170584\PSR3\AbstractLogger::critical()
             * @uses \Serega170584\PSR3\AbstractLogger::error()
             * @uses \Serega170584\PSR3\AbstractLogger::warning()
             * @uses \Serega170584\PSR3\AbstractLogger::notice()
             * @uses \Serega170584\PSR3\AbstractLogger::info()
             * @uses \Serega170584\PSR3\AbstractLogger::debug()
             */
            $this->logger->{$val}('123');
            $dateTime = new \DateTime();
            $dateTimeStr = $dateTime->format(\DateTime::RFC3339);
            $content = ob_get_contents();
            ob_end_clean();
            $this->assertEquals("{$dateTimeStr} [{$val}] 123" . PHP_EOL, $content);
        }, array_keys(AbstractLogger::LEVELS));
    }
}
