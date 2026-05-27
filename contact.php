<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit;
}

$nome = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$mensagem = trim($_POST['message'] ?? '');

if ($nome === '' || $email === '' || $mensagem === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'E-mail inválido.']);
    exit;
}

require_once __DIR__ . '/smtp-config.php';
require_once __DIR__ . '/smtp-mailer.php';
require_once __DIR__ . '/layouts/email_template.php';

$destino = $smtpConfig['to'];
$assunto = "Nova mensagem de {$nome} — Portfólio";
$data = date('d/m/Y \à\s H:i');
$htmlBody = buildEmailHtml($nome, $email, $mensagem, $data);

$result = smtp_send_mail([
    'host' => $smtpConfig['host'],
    'port' => $smtpConfig['port'],
    'user' => $smtpConfig['user'],
    'pass' => $smtpConfig['pass'],
    'from' => $smtpConfig['user'],
    'fromName' => 'Portfólio Bianca',
    'to' => $destino,
    'toName' => 'Bianca Aline',
    'subject' => $assunto,
    'body' => $htmlBody,
    'replyTo' => $email,
    'replyToName' => $nome,
]);

if ($result['success']) {
    echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso!']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao enviar mensagem. Tente novamente.']);
}
