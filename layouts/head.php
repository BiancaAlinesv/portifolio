<?php
declare(strict_types=1);

$c = require_once __DIR__ . '/../config.php';

$site = $c['site'];
$person = $c['person'];
$social = $c['social'];
$skills = $c['skills'];
$projects = $c['projects'];
$nav = $c['nav'];

$currentYear = date('Y');

$schemaPerson = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ProfilePage',
    'name' => $site['name'],
    'url' => $site['url'] . '/',
    'description' => $site['description'],
    'mainEntity' => [
        '@type' => 'Person',
        'name' => $person['name'],
        'givenName' => $person['given_name'],
        'familyName' => $person['family_name'],
        'jobTitle' => $person['job_title'],
        'honorificPrefix' => 'Sra.',
        'description' => 'Desenvolvedora web com experiência em projetos digitais, automações com IA e soluções elegantes para a web.',
        'url' => $site['url'],
        'email' => $person['email'],
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Sorocaba',
            'addressRegion' => 'SP',
            'addressCountry' => 'BR',
        ],
        'sameAs' => array_map(fn($s) => $s['url'], array_filter($social, fn($s) => str_starts_with($s['url'], 'http'))),
        'knowsAbout' => ['Desenvolvimento Web', 'JavaScript', 'CSS', 'HTML5', 'Python', 'SQL', 'Inteligência Artificial', 'UX Design', 'Acessibilidade Web'],
        'alumniOf' => [
            '@type' => 'Organization',
            'name' => 'Em formação contínua',
        ],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$schemaWebSite = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => $site['name'],
    'url' => $site['url'] . '/',
    'inLanguage' => 'pt-BR',
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => $site['url'] . '/?q={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($site['language']) ?>" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($site['title']) ?></title>
<meta name="description" content="<?= htmlspecialchars($site['description']) ?>">
<meta name="keywords" content="desenvolvedora web, portfólio, currículo online, IA, inteligência artificial, frontend, JavaScript, Python, SQL, UX, web design, Bianca Aline, Sorocaba">
<meta name="author" content="<?= htmlspecialchars($person['name']) ?>">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
<link rel="canonical" href="<?= htmlspecialchars($site['url']) ?>/">

<meta name="theme-color" content="<?= htmlspecialchars($site['theme_color']) ?>">
<meta name="apple-mobile-web-app-title" content="<?= htmlspecialchars($person['name']) ?>">

<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚡</text></svg>">
<link rel="apple-touch-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚡</text></svg>">

<meta property="og:type" content="website">
<meta property="og:url" content="<?= htmlspecialchars($site['url']) ?>/">
<meta property="og:title" content="<?= htmlspecialchars($site['title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($site['og_description']) ?>">
<meta property="og:site_name" content="<?= htmlspecialchars($person['name']) ?>">
<meta property="og:locale" content="<?= htmlspecialchars($site['locale']) ?>">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($site['title']) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($site['og_description']) ?>">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@300;400;500&family=Outfit:wght@300;400;500;600&display=swap">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@300;400;500&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/styles.css">

<script type="application/ld+json"><?= $schemaPerson ?></script>
<script type="application/ld+json"><?= $schemaWebSite ?></script>
</head>
