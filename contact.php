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
$telefone = trim($_POST['phone'] ?? '');
$mensagem = trim($_POST['message'] ?? '');

if ($nome === '' || $email === '' || $mensagem === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos obrigatórios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'E-mail inválido.']);
    exit;
}

if ($telefone !== '') {
    $digits = preg_replace('/\D/', '', $telefone);
    if (strlen($digits) < 10 || strlen($digits) > 11) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Telefone inválido. Informe DDD + número.']);
        exit;
    }
}

require_once __DIR__ . '/smtp-config.php';
require_once __DIR__ . '/smtp-mailer.php';
require_once __DIR__ . '/layouts/email_template.php';

$assunto = "Nova mensagem de {$nome} — Portfólio";
$data = date('d/m/Y \à\s H:i');
$htmlBody = buildEmailHtml($nome, $email, $telefone, $mensagem, $data);

$result = smtp_send_mail([
    'host' => $smtpConfig['host'],
    'port' => $smtpConfig['port'],
    'user' => $smtpConfig['user'],
    'pass' => $smtpConfig['pass'],
    'from' => $smtpConfig['from'],
    'fromName' => $smtpConfig['fromName'],
    'to' => $smtpConfig['to'],
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
