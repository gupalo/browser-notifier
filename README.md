BrowserNotifier
===============

Simple use of Symfony Notifier.

Install
-------

Composer

    composer require gupalo/browser-notifier

Create `config/packages/browser_notifier.yaml`

    imports:
        - {resource: '../../vendor/gupalo/browser-notifier/config/services.yaml'}

Usage
-----

Controller

    public function ...(..., BrowserNotifier $notifier): Response
    {
        ...
        $notifier->send('Something');
        $notifier->success('Something');
        $notifier->warning('Something');
        $notifier->error('Something');
        ...
    }

Twig

    {{ app.request.hasPreviousSession ? app.flashes('notification')|browser_notifications|raw : '' }}

Use `BrowserLogger` as logger implementing `LoggerInterface`.
