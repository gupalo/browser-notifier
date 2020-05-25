<?php

namespace Gupalo\BrowserNotifier\Twig;

use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class BrowserNotificationExtensionTest extends TestCase
{
    private BrowserNotificationExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new BrowserNotificationExtension();
    }

    public function testGetFilters(): void
    {
        $filters = $this->extension->getFilters();

        $this->assertIsArray($filters);
        $this->assertInstanceOf(TwigFilter::class, $filters[0]);
    }

    public function testBrowserNotifications(): void
    {
        // normal
        $this->assertSame(
            implode("\n", [
                '<div class="alert alert-info alert-dismissible" role="alert">test1<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
                '<div class="alert alert-info alert-dismissible" role="alert">test2<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
            ]),
            $this->extension->browserNotifications(['test1', 'test2'])
        );
        $this->assertSame(
            implode("\n", [
                '<div class="alert alert-info alert-dismissible" role="alert">test1<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
                '<div class="alert alert-info alert-dismissible" role="alert">&lt;b&gt;test&lt;/b&gt;<br />',
                'test&lt;br&gt;<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
            ]),
            $this->extension->browserNotifications(['test1', '<b>test</b>' . "\n" . 'test<br>'])
        );

        // strip
        $this->assertSame(
            implode("\n", [
                '<div class="alert alert-info alert-dismissible" role="alert">test1<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
                '<div class="alert alert-info alert-dismissible" role="alert">test2<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
            ]),
            $this->extension->browserNotifications(['test1', 'test2'], 'strip')
        );
        $this->assertSame(
            implode("\n", [
                '<div class="alert alert-info alert-dismissible" role="alert">test1<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
                '<div class="alert alert-info alert-dismissible" role="alert"><b>test</b><br />',
                'test<br><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
            ]),
            $this->extension->browserNotifications(['test1', '<b>test</b>' . "\n" . 'test<br>'], 'strip')
        );

        // raw
        $this->assertSame(
            implode("\n", [
                '<div class="alert alert-info alert-dismissible" role="alert">test1<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
                '<div class="alert alert-info alert-dismissible" role="alert">test2<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
            ]),
            $this->extension->browserNotifications(['test1', 'test2'], 'raw')
        );
        $this->assertSame(
            implode("\n", [
                '<div class="alert alert-info alert-dismissible" role="alert">test1<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
                '<div class="alert alert-info alert-dismissible" role="alert"><b>test</b>',
                'test<br><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
            ]),
            $this->extension->browserNotifications(['test1', '<b>test</b>' . "\n" . 'test<br>'], 'raw')
        );
    }

    public function testBrowserNotification(): void
    {
        $this->assertSame(
            '<div class="alert alert-info alert-dismissible" role="alert">test<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
            $this->extension->browserNotification('test')
        );
        $this->assertSame(
            implode("\n", [
                '<div class="alert alert-info alert-dismissible" role="alert">&lt;b&gt;test&lt;/b&gt;<br />',
                'test&lt;br&gt;<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
            ]),
            $this->extension->browserNotification('<b>test</b>' . "\n" . 'test<br>')
        );
    }

    public function testBrowserNotificationStrip(): void
    {
        $this->assertSame(
            '<div class="alert alert-info alert-dismissible" role="alert">test<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
            $this->extension->browserNotificationStrip('test')
        );
        $this->assertSame(
            implode("\n", [
                '<div class="alert alert-info alert-dismissible" role="alert"><b>test</b><br />',
                'test<br><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
            ]),
            $this->extension->browserNotificationStrip('<b>test</b>' . "\n" . 'test<br>')
        );
    }

    public function testBrowserNotificationRaw(): void
    {
        $this->assertSame(
            '<div class="alert alert-info alert-dismissible" role="alert">test<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
            $this->extension->browserNotificationRaw('test')
        );
        $this->assertSame(
            implode("\n", [
                '<div class="alert alert-info alert-dismissible" role="alert"><b>test</b>',
                'test<br><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>',
            ]),
            $this->extension->browserNotificationRaw('<b>test</b>' . "\n" . 'test<br>')
        );
    }

    public function testBrowserNotificationEmoji(): void
    {
        $this->assertSame('success', $this->extension->browserNotificationEmoji('[success] test'));
    }

    public function testBrowserNotificationText(): void
    {
        $this->assertSame('test', $this->extension->browserNotificationText('[success] test'));
    }
}
