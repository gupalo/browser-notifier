<?php

/** @noinspection PhpParamsInspection */
/** @noinspection PhpUndefinedMethodInspection */

namespace Gupalo\BrowserNotifier\Tests;

use Gupalo\BrowserNotifier\BrowserLogger;
use Gupalo\BrowserNotifier\BrowserNotifier;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Log\LogLevel;

class BrowserLoggerTest extends TestCase
{
    use ProphecyTrait;

    /** @var BrowserNotifier */
    private $notifier;

    private BrowserLogger $logger;

    protected function setUp(): void
    {
        $this->notifier = $this->prophesize(BrowserNotifier::class);
        $this->logger = new BrowserLogger($this->notifier->reveal(), LogLevel::DEBUG);
    }

    public function testCritical(): void
    {
        $this->logger->critical('test', ['context' => 'is_skipped']);

        $this->notifier->error('test')->shouldBeCalledOnce();
    }

    public function testEmergency(): void
    {
        $this->logger->emergency('test', ['context' => 'is_skipped']);

        $this->notifier->error('test')->shouldBeCalledOnce();
    }

    public function testAlert(): void
    {
        $this->logger->alert('test', ['context' => 'is_skipped']);

        $this->notifier->error('test')->shouldBeCalledOnce();
    }

    public function testError(): void
    {
        $this->logger->error('test', ['context' => 'is_skipped']);

        $this->notifier->error('test')->shouldBeCalledOnce();
    }

    public function testWarning(): void
    {
        $this->logger->warning('test', ['context' => 'is_skipped']);

        $this->notifier->warning('test')->shouldBeCalledOnce();
    }

    public function testNotice(): void
    {
        $this->logger->notice('test', ['context' => 'is_skipped']);

        $this->notifier->warning('test')->shouldBeCalledOnce();
    }

    public function testInfo(): void
    {
        $this->logger->info('test', ['context' => 'is_skipped']);

        $this->notifier->send('test')->shouldBeCalledOnce();
    }

    public function testDebug(): void
    {
        $this->logger->debug('test', ['context' => 'is_skipped']);

        $this->notifier->send('test')->shouldBeCalledOnce();
    }

    public function testLog(): void
    {
        $this->logger->log(LogLevel::DEBUG, 'test', ['context' => 'is_skipped']);

        $this->notifier->send('test')->shouldBeCalledOnce();
    }

    public function testLog_Critical(): void
    {
        $this->logger->log(LogLevel::CRITICAL, 'test', ['context' => 'is_skipped']);

        $this->notifier->error('test')->shouldBeCalledOnce();
    }

    public function testLog_Emergency(): void
    {
        $this->logger->log(LogLevel::EMERGENCY, 'test', ['context' => 'is_skipped']);

        $this->notifier->error('test')->shouldBeCalledOnce();
    }

    public function testLog_Alert(): void
    {
        $this->logger->log(LogLevel::ALERT, 'test', ['context' => 'is_skipped']);

        $this->notifier->error('test')->shouldBeCalledOnce();
    }

    public function testLog_Error(): void
    {
        $this->logger->log(LogLevel::ERROR, 'test', ['context' => 'is_skipped']);

        $this->notifier->error('test')->shouldBeCalledOnce();
    }

    public function testLog_Warning(): void
    {
        $this->logger->log(LogLevel::WARNING, 'test', ['context' => 'is_skipped']);

        $this->notifier->warning('test')->shouldBeCalledOnce();
    }

    public function testLog_Notice(): void
    {
        $this->logger->log(LogLevel::NOTICE, 'test', ['context' => 'is_skipped']);

        $this->notifier->warning('test')->shouldBeCalledOnce();
    }

    public function testLog_Info(): void
    {
        $this->logger->log(LogLevel::INFO, 'test', ['context' => 'is_skipped']);

        $this->notifier->send('test')->shouldBeCalledOnce();
    }

    public function testLog_Debug(): void
    {
        $this->logger->log(LogLevel::DEBUG, 'test', ['context' => 'is_skipped']);

        $this->notifier->send('test')->shouldBeCalledOnce();
    }

    public function testGetMaxLevel(): void
    {
        $this->assertSame(0, $this->logger->getMaxLevel());

        $this->logger->log(LogLevel::DEBUG, 'test', ['context' => 'is_skipped']);

        $this->notifier->send('test')->shouldBeCalledOnce();

        $this->assertSame(BrowserLogger::DEBUG, $this->logger->getMaxLevel());
    }

    public function testSetMaxLevel(): void
    {
        $this->assertSame(0, $this->logger->getMaxLevel());

        $this->logger->setMaxLevel(BrowserLogger::NOTICE);
        $this->assertSame(BrowserLogger::NOTICE, $this->logger->getMaxLevel());

        $this->logger->setMaxLevel(LogLevel::WARNING);
        $this->assertSame(BrowserLogger::WARNING, $this->logger->getMaxLevel());
    }
}
