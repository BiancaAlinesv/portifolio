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

$destino = 'bianca.alinedev@gmail.com';
$assunto = "=?UTF-8?B?" . base64_encode("Nova mensagem de {$nome} — Portfólio") . "?=";

require_once __DIR__ . '/layouts/email_template.php';
$data = date('d/m/Y \à\s H:i');
$htmlBody = buildEmailHtml($nome, $email, $mensagem, $data);

$headers  = "From: Portfólio <bianca.alinedev@gmail.com>\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$enviado = mail($destino, $assunto, $htmlBody, $headers);

if ($enviado) {
    echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso!']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao enviar mensagem. Tente novamente.']);
}
