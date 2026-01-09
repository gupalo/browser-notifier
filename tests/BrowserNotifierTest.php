<?php

namespace Gupalo\BrowserNotifier\Tests;

use Gupalo\BrowserNotifier\BrowserNotifier;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

class BrowserNotifierTest extends TestCase
{
    private MockObject&NotifierInterface $notifier;

    private BrowserNotifier $browserNotifier;

    protected function setUp(): void
    {
        $this->notifier = $this->createMock(NotifierInterface::class);
        $this->browserNotifier = new BrowserNotifier($this->notifier);
    }

    public function testSend(): void
    {
        $this->notifier->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Notification $notification): bool {
                $this->assertSame('test', $notification->getSubject());
                return true;
            }));

        $this->browserNotifier->send('test');
    }

    public function testSend_Multiple(): void
    {
        $this->notifier->expects($this->exactly(3))
            ->method('send')
            ->with($this->callback(function (Notification $notification): bool {
                $this->assertSame('test', $notification->getSubject());
                return true;
            }));

        $this->browserNotifier->send(['test', 'test', 'test']);
    }

    public function testSuccess(): void
    {
        $this->notifier->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Notification $notification): bool {
                $this->assertSame('test', $notification->getSubject());
                $this->assertSame('[success]', $notification->getEmoji());
                return true;
            }));

        $this->browserNotifier->success('test');
    }

    public function testWarning(): void
    {
        $this->notifier->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Notification $notification): bool {
                $this->assertSame('test', $notification->getSubject());
                $this->assertSame('[warning]', $notification->getEmoji());
                return true;
            }));

        $this->browserNotifier->warning('test');
    }

    public function testError(): void
    {
        $this->notifier->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Notification $notification): bool {
                $this->assertSame('test', $notification->getSubject());
                $this->assertSame('[error]', $notification->getEmoji());
                return true;
            }));

        $this->browserNotifier->error('test');
    }

    public function testExtractEmoji(): void
    {
        $this->assertSame('', BrowserNotifier::extractEmoji('test'));
        $this->assertSame('success', BrowserNotifier::extractEmoji('[success] test'));
        $this->assertSame('', BrowserNotifier::extractEmoji('[success]] test'));
        $this->assertSame('success', BrowserNotifier::extractEmoji('[success]'));
        $this->assertSame('success', BrowserNotifier::extractEmoji('[success] [error] test'));
    }

    public function testExtractText(): void
    {
        $this->assertSame('test', BrowserNotifier::extractText('test'));
        $this->assertSame('test', BrowserNotifier::extractText('[success] test'));
        $this->assertSame('[success]] test', BrowserNotifier::extractText('[success]] test'));
        $this->assertSame('', BrowserNotifier::extractText('[success]'));
        $this->assertSame('[error] test', BrowserNotifier::extractText('[success] [error] test'));
    }
}
