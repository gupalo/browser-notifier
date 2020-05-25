<?php

namespace Gupalo\BrowserNotifier;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

class BrowserNotifier
{
    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * @param string|string[] $s
     */
    public function send($s): void
    {
        $this->doSend($s);
    }

    /**
     * @param string|string[] $s
     */
    public function success($s): void
    {
        $this->doSend($s, '[success]');
    }

    /**
     * @param string|string[] $s
     */
    public function warning($s): void
    {
        $this->doSend($s, '[warning]');
    }

    /**
     * @param string|string[] $s
     */
    public function error($s): void
    {
        $this->doSend($s, '[error]');
    }

    /**
     * @param string|array $s
     * @param string|null $emoji
     */
    private function doSend($s, string $emoji = null): void
    {
        if (is_array($s)) {
            foreach ($s as $item) {
                $this->doSend($item, $emoji);
            }

            return;
        }

        $notification = new Notification($s, ['browser']);
        if ($emoji !== null) {
            $notification->emoji($emoji);
        }
        $this->notifier->send($notification);
    }

    public static function extractEmoji(string $s): string
    {
        return preg_match('#^\[([^]]+)](\s+|$)#', $s, $m) ? $m[1] : '';
    }

    public static function extractText(string $s): string
    {
        return preg_replace('#^\[([^]]+)](\s+|$)#', '', $s);
    }
}
