<?php /** @noinspection PhpUndefinedMethodInspection *//** @noinspection PhpParamsInspection */

namespace Gupalo\BrowserNotifier\Tests;

use Gupalo\BrowserNotifier\BrowserNotifier;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

class BrowserNotifierTest extends TestCase
{
    use ProphecyTrait;

    /** @var NotifierInterface */
    private $notifier;

    private BrowserNotifier $browserNotifier;

    protected function setUp(): void
    {
        $this->notifier = $this->prophesize(NotifierInterface::class);

        $this->browserNotifier = new BrowserNotifier($this->notifier->reveal());
    }

    public function testSend(): void
    {
        $this->notifier->send(Argument::that(function($v){
            /** @var Notification $v */
            $this->assertInstanceOf(Notification::class, $v);
            $this->assertSame('test', $v->getSubject());

            return true;
        }))->shouldBeCalledOnce();

        $this->browserNotifier->send('test');
    }

    public function testSend_Multiple(): void
    {
        $this->notifier->send(Argument::that(function($v){
            /** @var Notification $v */
            $this->assertInstanceOf(Notification::class, $v);
            $this->assertSame('test', $v->getSubject());

            return true;
        }))->shouldBeCalledTimes(3);

        $this->browserNotifier->send(['test', 'test', 'test']);
    }

    public function testSuccess(): void
    {
        $this->notifier->send(Argument::that(function($v){
            /** @var Notification $v */
            $this->assertInstanceOf(Notification::class, $v);
            $this->assertSame('test', $v->getSubject());
            $this->assertSame('[success]', $v->getEmoji());

            return true;
        }))->shouldBeCalledOnce();

        $this->browserNotifier->success('test');
    }

    public function testWarning(): void
    {
        $this->notifier->send(Argument::that(function($v){
            /** @var Notification $v */
            $this->assertInstanceOf(Notification::class, $v);
            $this->assertSame('test', $v->getSubject());
            $this->assertSame('[warning]', $v->getEmoji());

            return true;
        }))->shouldBeCalledOnce();

        $this->browserNotifier->warning('test');
    }

    public function testError(): void
    {
        $this->notifier->send(Argument::that(function($v){
            /** @var Notification $v */
            $this->assertInstanceOf(Notification::class, $v);
            $this->assertSame('test', $v->getSubject());
            $this->assertSame('[error]', $v->getEmoji());

            return true;
        }))->shouldBeCalledOnce();

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
