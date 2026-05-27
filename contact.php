<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit;
}

session_start();

$rateLimit = 10;
if (isset($_SESSION['last_contact_submit'])) {
    $elapsed = time() - (int) $_SESSION['last_contact_submit'];
    if ($elapsed < $rateLimit) {
        $wait = $rateLimit - $elapsed;
        http_response_code(429);
        echo json_encode(['success' => false, 'message' => 'Aguaste ' . $wait . ' segundo' . ($wait > 1 ? 's' : '') . ' antes de enviar novamente.']);
        exit;
    }
}

function cleanInput(string $value): string
{
    $value = trim($value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $value;
}

function detectXSS(string $value): bool
{
    $patterns = [
        '/<\s*\/?\s*(script|iframe|object|embed|form|input|link|meta|style|base|body|img|svg)\b[^>]*>/i',
        '/\bon\w+\s*=/i',
        '/javascript\s*:/i',
        '/vbscript\s*:/i',
        '/data\s*:\s*text\/html/i',
    ];
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $value)) {
            return true;
        }
    }
    return false;
}

$nomeRaw = trim($_POST['name'] ?? '');
$emailRaw = trim($_POST['email'] ?? '');
$telefoneRaw = trim($_POST['phone'] ?? '');
$mensagemRaw = trim($_POST['message'] ?? '');

if ($nomeRaw === '' || $emailRaw === '' || $mensagemRaw === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos obrigatórios.']);
    exit;
}

if (mb_strlen($nomeRaw) < 2 || mb_strlen($nomeRaw) > 100) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Nome deve ter entre 2 e 100 caracteres.']);
    exit;
}

if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'.-]+$/', $nomeRaw)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Nome contém caracteres não permitidos.']);
    exit;
}

if (detectXSS($nomeRaw)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Nome contém conteúdo não permitido.']);
    exit;
}

if (mb_strlen($emailRaw) > 120) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'E-mail muito longo.']);
    exit;
}

if (!filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'E-mail inválido.']);
    exit;
}

if (detectXSS($emailRaw)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'E-mail contém conteúdo não permitido.']);
    exit;
}

if ($telefoneRaw !== '') {
    $digits = preg_replace('/\D/', '', $telefoneRaw);
    if (strlen($digits) < 10 || strlen($digits) > 11) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Telefone inválido. Informe DDD + número.']);
        exit;
    }
    if (detectXSS($telefoneRaw)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Telefone contém conteúdo não permitido.']);
        exit;
    }
}

if (mb_strlen($mensagemRaw) < 10 || mb_strlen($mensagemRaw) > 2000) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Mensagem deve ter entre 10 e 2000 caracteres.']);
    exit;
}

if (detectXSS($mensagemRaw)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Mensagem contém conteúdo não permitido.']);
    exit;
}

$nome = cleanInput($nomeRaw);
$email = cleanInput($emailRaw);
$telefone = cleanInput($telefoneRaw);
$mensagem = cleanInput($mensagemRaw);

require_once __DIR__ . '/smtp-config.php';
require_once __DIR__ . '/smtp-mailer.php';
require_once __DIR__ . '/layouts/email_template.php';

$_SESSION['last_contact_submit'] = time();

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
