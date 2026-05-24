<?php
declare(strict_types=1);

function buildEmailHtml(string $nome, string $email, string $mensagem, string $data): string
{
    $escNome = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
    $escEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $escMensagem = nl2br(htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'));
    $escData = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    return <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nova mensagem do portfólio</title>
</head>
<body style="margin:0;padding:0;background:#0e0d0c;font-family:'Outfit',Arial,Helvetica,sans-serif;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0e0d0c;min-height:100vh;">
<tr>
<td align="center" style="padding:40px 16px;">

<table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;">

<!-- Header com logo -->
<tr>
<td style="padding:32px 40px 24px;background:linear-gradient(135deg,#161412 0%,#1c1916 100%);border-radius:12px 12px 0 0;border-bottom:1px solid rgba(168,85,247,0.15);">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td>
<span style="font-family:'Courier New',monospace;font-size:13px;color:#a855f7;letter-spacing:0.02em;">{</span>
<span style="font-family:'Courier New',monospace;font-size:15px;color:#f0ece4;font-weight:500;letter-spacing:0.02em;">ba</span>
<span style="font-family:'Courier New',monospace;font-size:13px;color:#a855f7;letter-spacing:0.02em;">}</span>
</td>
<td align="right">
<span style="font-family:'Courier New',monospace;font-size:10px;color:#8b7fc3;letter-spacing:0.12em;text-transform:uppercase;">Nova mensagem</span>
</td>
</tr>
</table>
</td>
</tr>

<!-- Badge de destaque -->
<tr>
<td style="padding:28px 40px 0;background:#161412;">
<table role="presentation" cellpadding="0" cellspacing="0">
<tr>
<td style="background:linear-gradient(135deg,#7c3aed,#a855f7);padding:6px 16px;border-radius:20px;">
<span style="font-family:'Courier New',monospace;font-size:10px;color:#ffffff;letter-spacing:0.10em;text-transform:uppercase;">{$escData}</span>
</td>
</tr>
</table>
</td>
</tr>

<!-- Título -->
<tr>
<td style="padding:20px 40px 8px;background:#161412;">
<h1 style="margin:0;font-family:Georgia,'Times New Roman',serif;font-size:28px;font-weight:400;font-style:italic;color:#f0ece4;line-height:1.2;">Nova mensagem<br><span style="color:#a855f7;">do portfólio</span></h1>
</td>
</tr>

<!-- Subtítulo -->
<tr>
<td style="padding:8px 40px 28px;background:#161412;">
<p style="margin:0;font-size:14px;color:#8b7fc3;line-height:1.6;font-weight:300;">Alguém entrou em contato através do formulário de contato do seu portfólio.</p>
</td>
</tr>

<!-- Card com dados do remetente -->
<tr>
<td style="padding:0 40px;background:#161412;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#1c1916;border-radius:10px;border:1px solid rgba(168,85,247,0.10);overflow:hidden;">
<!-- Nome -->
<tr>
<td style="padding:20px 24px 12px;border-bottom:1px solid rgba(255,255,255,0.05);">
<span style="font-family:'Courier New',monospace;font-size:9px;color:#8b7fc3;letter-spacing:0.12em;text-transform:uppercase;display:block;margin-bottom:4px;">Nome</span>
<span style="font-size:16px;color:#f0ece4;font-weight:500;">{$escNome}</span>
</td>
</tr>
<!-- Email -->
<tr>
<td style="padding:12px 24px;border-bottom:1px solid rgba(255,255,255,0.05);">
<span style="font-family:'Courier New',monospace;font-size:9px;color:#8b7fc3;letter-spacing:0.12em;text-transform:uppercase;display:block;margin-bottom:4px;">Email</span>
<a href="mailto:{$escEmail}" style="font-size:14px;color:#a855f7;text-decoration:none;">{$escEmail}</a>
</td>
</tr>
<!-- Mensagem -->
<tr>
<td style="padding:12px 24px 20px;">
<span style="font-family:'Courier New',monospace;font-size:9px;color:#8b7fc3;letter-spacing:0.12em;text-transform:uppercase;display:block;margin-bottom:8px;">Mensagem</span>
<span style="font-size:14px;color:#d4cfc6;line-height:1.75;font-weight:300;display:block;">{$escMensagem}</span>
</td>
</tr>
</table>
</td>
</tr>

<!-- Botão responder -->
<tr>
<td style="padding:28px 40px 32px;background:#161412;" align="center">
<a href="mailto:{$escEmail}" style="display:inline-block;background:linear-gradient(135deg,#a855f7,#7c3aed);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:6px;font-size:14px;font-weight:500;letter-spacing:0.02em;box-shadow:0 8px 24px rgba(168,85,247,0.30);">Responder mensagem →</a>
</td>
</tr>

<!-- Divisor -->
<tr>
<td style="padding:0 40px;background:#161412;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="height:1px;background:linear-gradient(90deg,transparent,rgba(168,85,247,0.30),transparent);"></td>
</tr>
</table>
</td>
</tr>

<!-- Footer -->
<tr>
<td style="padding:24px 40px 32px;background:#161412;border-radius:0 0 12px 12px;" align="center">
<span style="font-family:Georgia,'Times New Roman',serif;font-size:13px;font-style:italic;color:#8b7fc3;">Bianca Aline</span>
<br>
<span style="font-family:'Courier New',monospace;font-size:9px;color:#5a5470;letter-spacing:0.08em;text-transform:uppercase;">Portfólio · Contato</span>
</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>
HTML;
}
