# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

A PHP library that extends Symfony Notifier for browser-based flash notifications. Provides a simple API for sending notifications with different severity levels (info, success, warning, error) and includes Twig integration for rendering Bootstrap-style alerts.

## Commands

```bash
# Install dependencies
composer install

# Run all tests
vendor/bin/phpunit

# Run a single test file
vendor/bin/phpunit tests/BrowserNotifierTest.php

# Run a specific test method
vendor/bin/phpunit --filter testSend tests/BrowserNotifierTest.php

# Static analysis
vendor/bin/phpstan analyse
```

## Architecture

**Core Classes (src/):**
- `BrowserNotifier` - Main service wrapping Symfony's NotifierInterface. Sends notifications via `send()`, `success()`, `warning()`, `error()` methods. Uses emoji markers (`[success]`, `[warning]`, `[error]`) to encode severity in notification subjects.
- `BrowserLogger` - PSR-3 LoggerInterface implementation that forwards log messages to BrowserNotifier. Maps log levels to notification types (error/critical/alert/emergency → error, warning/notice → warning, others → info).
- `Twig/BrowserNotificationExtension` - Twig filters for rendering flash messages as Bootstrap alerts. Filters: `browser_notifications`, `browser_notification`, `browser_notification_strip`, `browser_notification_raw`, `browser_notification_emoji`, `browser_notification_text`.

**Notification Flow:**
1. BrowserNotifier wraps messages with emoji markers and sends via Symfony Notifier's "browser" channel
2. Twig extension extracts emoji markers from flash messages and maps them to Bootstrap alert classes (success→success, warning→warning, error→danger, default→info)

## Testing

Uses PHPUnit with Prophecy for mocking. Tests use `ProphecyTrait` and mock Symfony's `NotifierInterface`.

## Requirements

- PHP >= 8.4
- Symfony Notifier ^7.4|^8.0
- Twig ^3.22
