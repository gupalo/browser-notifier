<?php

namespace Gupalo\BrowserNotifier\Twig;

use Gupalo\BrowserNotifier\BrowserNotifier;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BrowserNotificationExtension extends AbstractExtension
{
    private array $classes = ['' => 'info', 'success' => 'success', 'warning' => 'warning', 'error' => 'danger'];

    private string $template = '<div class="alert alert-{class} alert-dismissible" role="alert">{message}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button></div>';

    private array $safeHtmlTags = ['a', 'b', 'i', 'strong', 'em', 'u', 'span', 'br', 'p', 'ul', 'ol', 'li', 'table', 'tr', 'td', 'th', 'thead', 'tbody', 'tfoot', 'hr', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'h7'];

    public function getFilters(): array
    {
        return [
            new TwigFilter('browser_notifications', [$this, 'browserNotifications'], ['is_safe' => ['html']]),
            new TwigFilter('browser_notification', [$this, 'browserNotification'], ['is_safe' => ['html']]),
            new TwigFilter('browser_notification_strip', [$this, 'browserNotificationStrip'], ['is_safe' => ['html']]),
            new TwigFilter('browser_notification_raw', [$this, 'browserNotificationRaw'], ['is_safe' => ['html']]),
            new TwigFilter('browser_notification_emoji', [$this, 'browserNotificationEmoji'], ['is_safe' => ['html']]),
            new TwigFilter('browser_notification_text', [$this, 'browserNotificationText'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param array $a
     * @param string $type '' - htmlspecialchars (default), 'strip' - strip_tags, 'raw' - no escaping (dangerous!)
     * @return string
     */
    public function browserNotifications(array $a, string $type = ''): string
    {
        return implode("\n", array_map([$this, 'browserNotification' . ucfirst($type)], $a));
    }

    public function browserNotification(string $s): string
    {
        $text = nl2br(htmlspecialchars($this->browserNotificationText($s)));

        return $this->render($this->browserNotificationEmoji($s), $text);
    }

    public function browserNotificationStrip(string $s): string
    {
        $text = nl2br(strip_tags($this->browserNotificationText($s), $this->safeHtmlTags));

        return $this->render($this->browserNotificationEmoji($s), $text);
    }

    public function browserNotificationRaw(string $s): string
    {
        $text = $this->browserNotificationText($s);

        return $this->render($this->browserNotificationEmoji($s), $text);
    }

    public function browserNotificationEmoji(string $s): string
    {
        return BrowserNotifier::extractEmoji($s);
    }

    public function browserNotificationText(string $s): string
    {
        return BrowserNotifier::extractText($s);
    }

    protected function render(string $emoji, string $text)
    {
        $replaces = [
            '{class}' => $this->classes[$emoji] ?? $this->classes[''] ?? '',
            '{message}' => $text,
        ];

        return str_replace(array_keys($replaces), array_values($replaces), $this->template);
    }
}
