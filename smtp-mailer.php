<?php

declare(strict_types=1);

function smtp_send_mail(array $cfg): array
{
    $host = $cfg['host'];
    $port = $cfg['port'];
    $user = $cfg['user'];
    $pass = str_replace('-', '', $cfg['pass']);
    $from = $cfg['from'];
    $fromName = $cfg['fromName'];
    $to = $cfg['to'];
    $toName = $cfg['toName'];
    $subject = $cfg['subject'];
    $body = $cfg['body'];
    $replyTo = $cfg['replyTo'] ?? '';
    $replyToName = $cfg['replyToName'] ?? '';

    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ]);

    if ($port === 465) {
        $socket = @stream_socket_client(
            "ssl://{$host}:{$port}",
            $errno,
            $errstr,
            15,
            STREAM_CLIENT_CONNECT,
            $context
        );
    } else {
        $socket = @fsockopen($host, $port, $errno, $errstr, 15);
    }

    if (!$socket) {
        return ['success' => false, 'error' => "Conexão falhou: {$errstr} ({$errno})"];
    }

    stream_set_timeout($socket, 15);

    $log = [];

    $read = function () use ($socket, &$log) {
        $response = '';
        $startTime = time();
        while (!feof($socket)) {
            $line = @fgets($socket, 515);
            if ($line === false) {
                if (time() - $startTime > 10) break;
                usleep(10000);
                continue;
            }
            $response .= $line;
            $log[] = '< ' . trim($line);
            if (preg_match('/^\d{3}[\s]/', $line)) break;
        }
        return $response;
    };

    $write = function (string $cmd) use ($socket, &$log) {
        $log[] = '> ' . trim($cmd);
        fwrite($socket, $cmd . "\r\n");
    };

    $read();

    $write("EHLO {$host}");
    $ehlo = $read();

    if ($port !== 465) {
        $write("STARTTLS");
        $starttls = $read();

        if (!str_contains($starttls, '220')) {
            fclose($socket);
            return ['success' => false, 'error' => 'STARTTLS rejeitado: ' . $starttls];
        }

        if (!@stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            fclose($socket);
            return ['success' => false, 'error' => 'Falha ao iniciar TLS'];
        }

        $write("EHLO {$host}");
        $read();
    }

    $write("AUTH LOGIN");
    $authPrompt = $read();

    $write(base64_encode($user));
    $userResponse = $read();

    $write(base64_encode($pass));
    $authResponse = $read();

    if (!str_starts_with($authResponse, '235')) {
        fclose($socket);
        return ['success' => false, 'error' => 'Autenticação falhou. Verifique a senha de app.', 'log' => $log];
    }

    $write("MAIL FROM:<{$from}>");
    $fromResponse = $read();

    $write("RCPT TO:<{$to}>");
    $rcptResponse = $read();

    $write("DATA");
    $dataPrompt = $read();

    $subjectEncoded = "=?UTF-8?B?" . base64_encode($subject) . "?=";

    $headers = [];
    $headers[] = "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$from}>";
    $headers[] = "To: =?UTF-8?B?" . base64_encode($toName) . "?= <{$to}>";
    if ($replyTo) {
        $headers[] = "Reply-To: =?UTF-8?B?" . base64_encode($replyToName) . "?= <{$replyTo}>";
    }
    $headers[] = "Subject: {$subjectEncoded}";
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: text/html; charset=UTF-8";
    $headers[] = "Date: " . date('r');
    $headers[] = "X-Mailer: PHP-SMTP";

    $message = implode("\r\n", $headers) . "\r\n\r\n" . $body . "\r\n.";

    $write($message);
    $dataResponse = $read();

    $write("QUIT");
    $read();

    fclose($socket);

    if (str_starts_with($dataResponse, '250')) {
        return ['success' => true];
    }

    return ['success' => false, 'error' => 'Envio rejeitado: ' . trim($dataResponse), 'log' => $log];
}
