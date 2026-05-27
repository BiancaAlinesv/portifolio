<?php

declare(strict_types=1);

$envPath = __DIR__ . '/.env';

if (!file_exists($envPath)) {
    throw new RuntimeException('Arquivo .env não encontrado em: ' . $envPath);
}

$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    if (str_starts_with(trim($line), '#')) {
        continue;
    }

    if (!str_contains($line, '=')) {
        continue;
    }

    [$key, $value] = explode('=', $line, 2);
    $key = trim($key);
    $value = trim($value);

    if (preg_match('/^"(.*)"$/', $value, $m)) {
        $value = $m[1];
    }

    putenv("{$key}={$value}");
    $_ENV[$key] = $value;
}

$smtpConfig = [
    'host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'port' => (int) (getenv('SMTP_PORT') ?: 465),
    'user' => getenv('SMTP_USER') ?: '',
    'pass' => getenv('SMTP_PASS') ?: '',
    'from' => getenv('SMTP_FROM') ?: getenv('SMTP_USER') ?: '',
    'fromName' => getenv('SMTP_FROM_NAME') ?: 'Portfólio',
    'to' => getenv('SMTP_TO') ?: getenv('SMTP_USER') ?: '',
];
