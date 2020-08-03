<?php


namespace Serega170584\PSR3;

/**
 * Minimalist PSR-3 logger designed to write in stderr or any other stream.
 *
 * @author Sergey Krivobokov <serega170584@gmail.com>
 */
class Logger extends AbstractLogger
{

    /**
     * @var false|resource
     */
    private $handle;

    public function __construct($output = null)
    {
        if ($output && false === $this->handle = \is_resource($output) ? $output : @fopen($output, 'a')) {
            throw new \InvalidArgumentException(sprintf('Unable to open "%s".', $output));
        }
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        if (!isset(self::LEVELS[$level])) {
            throw new \InvalidArgumentException(sprintf('The log level "%s" does not exist.', $level));
        }

        if ($this->handle) {
            @fwrite($this->handle, $this->format($level, $message, $context));
        } else {
            error_log($this->format($level, $message, $context, false));
        }
    }

    public function format(string $level, string $message, array $context, bool $prefixDate = true): string
    {
        if (false !== strpos($message, '{')) {
            $replacements = [];
            foreach ($context as $key => $val) {
                if (null === $val || is_scalar($val) || (\is_object($val) && method_exists($val, '__toString'))) {
                    $replacements["{{$key}}"] = $val;
                } elseif ($val instanceof \DateTimeInterface) {
                    $replacements["{{$key}}"] = $val->format(\DateTime::RFC3339);
                } elseif (\is_object($val)) {
                    $replacements["{{$key}}"] = '[object '.\get_class($val).']';
                } else {
                    $replacements["{{$key}}"] = '['.\gettype($val).']';
                }
            }

            $message = strtr($message, $replacements);
        }

        $log = sprintf('[%s] %s', $level, $message).PHP_EOL;
        if ($prefixDate) {
            $log = date(\DateTime::RFC3339).' '.$log;
        }

        return $log;
    }
}