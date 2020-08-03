<?php

namespace Serega170584\PSR3;

/**
 * This is a simple Logger implementation that other Loggers can inherit from.
 *
 * It simply delegates all log-level-specific methods to the `log` method to
 * reduce boilerplate code that a simple Logger that does the same thing with
 * messages regardless of the error level has to implement.
 * @method emergency($message, array $context = array())
 * @method alert($message, array $context = array())
 * @method critical($message, array $context = array())
 * @method error($message, array $context = array())
 * @method warning($message, array $context = array())
 * @method notice($message, array $context = array())
 * @method info($message, array $context = array())
 * @method debug($message, array $context = array())
 */
abstract class AbstractLogger
{
    const DEBUG = 'debug';
    const INFO = 'info';
    const NOTICE = 'notice';
    const WARNING = 'warning';
    const ERROR = 'error';
    const CRITICAL = 'critical';
    const ALERT = 'alert';
    const EMERGENCY = 'emergency';
    const LEVELS = [
        self::DEBUG => 0,
        self::INFO => 1,
        self::NOTICE => 2,
        self::WARNING => 3,
        self::ERROR => 4,
        self::CRITICAL => 5,
        self::ALERT => 6,
        self::EMERGENCY => 7,
    ];

    public function __call($method, $args)
    {
        $interface = new \ReflectionClass('\Serega170584\PSR3\LoggerInterface');
        $searchedMethod = array_filter(array_map(function ($val) use ($method) {
            $res = false;
            /**
             * @var \ReflectionMethod $val
             */
            if ($val->getName() == $method) {
                $res = $method;
            }
            return $res;
        }, $interface->getMethods()));
        if ($searchedMethod){
            $searchedMethod=array_shift($searchedMethod);
            $this->log($searchedMethod, $args);
        } else {
            throw new \BadMethodCallException('Call to undefined method ' . get_class($this) . '::' . $method . '()');
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param mixed[] $context
     *
     * @return void
     */
    abstract protected function log($level, $message, array $context = array());
}