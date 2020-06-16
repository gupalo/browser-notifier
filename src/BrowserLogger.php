<?php

namespace Gupalo\BrowserNotifier;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class BrowserLogger implements LoggerInterface
{
    private BrowserNotifier $notifier;

    private const LEVEL_DEBUG = 100;
    private const LEVEL_INFO = 200;
    private const LEVEL_NOTICE = 250;
    private const LEVEL_WARNING = 300;
    private const LEVEL_ERROR = 400;
    private const LEVEL_CRITICAL = 500;
    private const LEVEL_ALERT = 550;
    private const LEVEL_EMERGENCY = 600;

    /** @var int[] From Monolog\Logger */
    private const LEVELS = [
        LogLevel::DEBUG     => self::LEVEL_DEBUG,
        LogLevel::INFO      => self::LEVEL_INFO,
        LogLevel::NOTICE    => self::LEVEL_NOTICE,
        LogLevel::WARNING   => self::LEVEL_WARNING,
        LogLevel::ERROR     => self::LEVEL_ERROR,
        LogLevel::CRITICAL  => self::LEVEL_CRITICAL,
        LogLevel::ALERT     => self::LEVEL_ALERT,
        LogLevel::EMERGENCY => self::LEVEL_EMERGENCY,
    ];

    private int $level;

    /**
     * @param BrowserNotifier $notifier
     * @param string|int $level
     */
    public function __construct(BrowserNotifier $notifier, $level = self::LEVEL_INFO)
    {
        $this->notifier = $notifier;

        if (is_int($level) || preg_match('#^\d+$#', $level)) {
            $this->level = (int)$level;
        } else {
            $this->level = self::LEVELS[$level] ?? self::LEVELS[LogLevel::INFO];
        }
    }

    public function emergency($message, array $context = []): void
    {
        if (self::LEVEL_EMERGENCY >= $this->level) {
            $this->notifier->error($message);
        }
    }

    public function alert($message, array $context = []): void
    {
        if (self::LEVEL_ALERT >= $this->level) {
            $this->notifier->error($message);
        }
    }

    public function critical($message, array $context = []): void
    {
        if (self::LEVEL_CRITICAL >= $this->level) {
            $this->notifier->error($message);
        }
    }

    public function error($message, array $context = []): void
    {
        if (self::LEVEL_ERROR >= $this->level) {
            $this->notifier->error($message);
        }
    }

    public function warning($message, array $context = []): void
    {
        if (self::LEVEL_WARNING >= $this->level) {
            $this->notifier->warning($message);
        }
    }

    public function notice($message, array $context = []): void
    {
        if (self::LEVEL_NOTICE >= $this->level) {
            $this->notifier->warning($message);
        }
    }

    public function info($message, array $context = []): void
    {
        if (self::LEVEL_INFO >= $this->level) {
            $this->notifier->send($message);
        }
    }

    public function debug($message, array $context = []): void
    {
        if (self::LEVEL_DEBUG >= $this->level) {
            $this->notifier->send($message);
        }
    }

    public function log($level, $message, array $context = []): void
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
                $this->emergency($message);
                break;
            case LogLevel::ALERT:
                $this->alert($message);
                break;
            case LogLevel::CRITICAL:
                $this->critical($message);
                break;
            case LogLevel::ERROR:
                $this->error($message);
                break;
            case LogLevel::WARNING:
                $this->warning($message);
                break;
            case LogLevel::NOTICE:
                $this->notice($message);
                break;
            case LogLevel::INFO:
                $this->info($message);
                break;
            default:
                $this->debug($message);
                break;
        }
    }
}
