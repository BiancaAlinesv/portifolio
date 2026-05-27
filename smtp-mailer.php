<?php

declare(strict_types=1);

function smtp_send_mail(array $cfg): array
{
    $host = $cfg['host'];
    $port = $cfg['port'];
    $user = $cfg['user'];
    $pass = $cfg['pass'];
    $from = $cfg['from'];
    $fromName = $cfg['fromName'];
    $to = $cfg['to'];
    $toName = $cfg['toName'];
    $subject = $cfg['subject'];
    $body = $cfg['body'];
    $replyTo = $cfg['replyTo'] ?? '';
    $replyToName = $cfg['replyToName'] ?? '';

    $socket = @fsockopen($host, $port, $errno, $errstr, 15);
    if (!$socket) {
        return ['success' => false, 'error' => "Conexão falhou: {$errstr} ({$errno})"];
    }

    $log = [];

    $read = function () use ($socket, &$log) {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            $log[] = '< ' . trim($line);
            if (preg_match('/^\d{3}\s/', $line)) break;
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

    $write("STARTTLS");
    $starttls = $read();

    if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
        fclose($socket);
        return ['success' => false, 'error' => 'Falha ao iniciar TLS'];
    }

    $write("EHLO {$host}");
    $ehlo2 = $read();

    $write("AUTH LOGIN");
    $read();

    $write(base64_encode($user));
    $read();

    $write(base64_encode($pass));
    $authResponse = $read();

    if (!str_starts_with($authResponse, '235')) {
        fclose($socket);
        return ['success' => false, 'error' => 'Autenticação falhou. Verifique a senha de app.'];
    }

    $write("MAIL FROM:<{$from}>");
    $read();

    $write("RCPT TO:<{$to}>");
    $read();

    $write("DATA");
    $read();

    $boundary = md5((string) time());
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

    return ['success' => false, 'error' => 'Envio rejeitado pelo servidor.'];
}
