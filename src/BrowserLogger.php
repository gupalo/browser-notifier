<?php

namespace Gupalo\BrowserNotifier;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;

class BrowserLogger implements LoggerInterface
{
    use LoggerTrait;

    private BrowserNotifier $notifier;

    private int $maxLevel = 0;

    public const DEBUG = 100;
    public const INFO = 200;
    public const NOTICE = 250;
    public const WARNING = 300;
    public const ERROR = 400;
    public const CRITICAL = 500;
    public const ALERT = 550;
    public const EMERGENCY = 600;

    /** @var int[] From Monolog\Logger */
    private const LEVELS = [
        LogLevel::DEBUG     => self::DEBUG,
        LogLevel::INFO      => self::INFO,
        LogLevel::NOTICE    => self::NOTICE,
        LogLevel::WARNING   => self::WARNING,
        LogLevel::ERROR     => self::ERROR,
        LogLevel::CRITICAL  => self::CRITICAL,
        LogLevel::ALERT     => self::ALERT,
        LogLevel::EMERGENCY => self::EMERGENCY,
    ];

    private int $level;

    /**
     * @param BrowserNotifier $notifier
     * @param string|int $level
     */
    public function __construct(BrowserNotifier $notifier, $level = self::INFO)
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
            $this->maxLevel = max($this->maxLevel, $intLevel);

            switch ($intLevel) {
                case self::EMERGENCY:
                case self::ALERT:
                case self::CRITICAL:
                case self::ERROR:
                    $this->notifier->error($message);
                    break;
                case self::WARNING:
                case self::NOTICE:
                    $this->notifier->warning($message);
                    break;
                default:
                    $this->notifier->send($message);
                    break;
            }
        }
    }

    public function getMaxLevel(): int
    {
        return $this->maxLevel;
    }

    /**
     * @param int|string $maxLevel
     * @return $this
     */
    public function setMaxLevel($maxLevel): self
    {
        $this->maxLevel = $this->intLevel($maxLevel);

        return $this;
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
