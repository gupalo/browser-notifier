<?php

namespace Gupalo\BrowserNotifier;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;

class BrowserLogger implements LoggerInterface
{
    use LoggerTrait;

    private BrowserNotifier $notifier;

    private bool $isDirty = false;

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

        $this->level = $this->intLevel($level);
    }

    /**
     * @param int|string $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = []): void
    {
        $intLevel = $this->intLevel($level);
        if ($intLevel >= $this->level) {
            $this->isDirty = true;

            switch ($this->intLevel($level)) {
                case self::LEVEL_EMERGENCY:
                case self::LEVEL_ALERT:
                case self::LEVEL_CRITICAL:
                case self::LEVEL_ERROR:
                    $this->notifier->error($message);
                    break;
                case self::LEVEL_WARNING:
                case self::LEVEL_NOTICE:
                    $this->notifier->warning($message);
                    break;
                default:
                    $this->notifier->send($message);
                    break;
            }
        }
    }

    public function isDirty(): bool
    {
        return $this->isDirty;
    }

    public function clear(): void
    {
        $this->isDirty = false;
    }

    /**
     * @param int|string $level
     * @return int
     */
    private function intLevel($level): int
    {
        if (is_int($level) || preg_match('#^\d+$#', $level)) {
            $result = (int)$level;
        } else {
            $result = self::LEVELS[$level] ?? self::LEVELS[LogLevel::INFO];
        }

        return $result;
    }
}
