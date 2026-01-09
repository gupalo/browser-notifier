<?php

namespace Gupalo\BrowserNotifier\Tests;

use Gupalo\BrowserNotifier\BrowserLogger;
use Gupalo\BrowserNotifier\BrowserNotifier;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class BrowserLoggerTest extends TestCase
{
    private MockObject&BrowserNotifier $notifier;

    private BrowserLogger $logger;

    protected function setUp(): void
    {
        $this->notifier = $this->createMock(BrowserNotifier::class);
        $this->logger = new BrowserLogger($this->notifier, LogLevel::DEBUG);
    }

    public function testCritical(): void
    {
        $this->notifier->expects($this->once())->method('error')->with('test');

        $this->logger->critical('test', ['context' => 'is_skipped']);
    }

    public function testEmergency(): void
    {
        $this->notifier->expects($this->once())->method('error')->with('test');

        $this->logger->emergency('test', ['context' => 'is_skipped']);
    }

    public function testAlert(): void
    {
        $this->notifier->expects($this->once())->method('error')->with('test');

        $this->logger->alert('test', ['context' => 'is_skipped']);
    }

    public function testError(): void
    {
        $this->notifier->expects($this->once())->method('error')->with('test');

        $this->logger->error('test', ['context' => 'is_skipped']);
    }

    public function testWarning(): void
    {
        $this->notifier->expects($this->once())->method('warning')->with('test');

        $this->logger->warning('test', ['context' => 'is_skipped']);
    }

    public function testNotice(): void
    {
        $this->notifier->expects($this->once())->method('warning')->with('test');

        $this->logger->notice('test', ['context' => 'is_skipped']);
    }

    public function testInfo(): void
    {
        $this->notifier->expects($this->once())->method('send')->with('test');

        $this->logger->info('test', ['context' => 'is_skipped']);
    }

    public function testDebug(): void
    {
        $this->notifier->expects($this->once())->method('send')->with('test');

        $this->logger->debug('test', ['context' => 'is_skipped']);
    }

    public function testLog(): void
    {
        $this->notifier->expects($this->once())->method('send')->with('test');

        $this->logger->log(LogLevel::DEBUG, 'test', ['context' => 'is_skipped']);
    }

    public function testLog_Critical(): void
    {
        $this->notifier->expects($this->once())->method('error')->with('test');

        $this->logger->log(LogLevel::CRITICAL, 'test', ['context' => 'is_skipped']);
    }

    public function testLog_Emergency(): void
    {
        $this->notifier->expects($this->once())->method('error')->with('test');

        $this->logger->log(LogLevel::EMERGENCY, 'test', ['context' => 'is_skipped']);
    }

    public function testLog_Alert(): void
    {
        $this->notifier->expects($this->once())->method('error')->with('test');

        $this->logger->log(LogLevel::ALERT, 'test', ['context' => 'is_skipped']);
    }

    public function testLog_Error(): void
    {
        $this->notifier->expects($this->once())->method('error')->with('test');

        $this->logger->log(LogLevel::ERROR, 'test', ['context' => 'is_skipped']);
    }

    public function testLog_Warning(): void
    {
        $this->notifier->expects($this->once())->method('warning')->with('test');

        $this->logger->log(LogLevel::WARNING, 'test', ['context' => 'is_skipped']);
    }

    public function testLog_Notice(): void
    {
        $this->notifier->expects($this->once())->method('warning')->with('test');

        $this->logger->log(LogLevel::NOTICE, 'test', ['context' => 'is_skipped']);
    }

    public function testLog_Info(): void
    {
        $this->notifier->expects($this->once())->method('send')->with('test');

        $this->logger->log(LogLevel::INFO, 'test', ['context' => 'is_skipped']);
    }

    public function testLog_Debug(): void
    {
        $this->notifier->expects($this->once())->method('send')->with('test');

        $this->logger->log(LogLevel::DEBUG, 'test', ['context' => 'is_skipped']);
    }

    public function testGetMaxLevel(): void
    {
        $this->notifier->expects($this->once())->method('send')->with('test');

        $this->assertSame(0, $this->logger->getMaxLevel());

        $this->logger->log(LogLevel::DEBUG, 'test', ['context' => 'is_skipped']);

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
