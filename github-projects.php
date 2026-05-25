<?php

declare(strict_types=1);

define('GITHUB_USERNAME', 'BiancaAlinesv');
define('GITHUB_CACHE_DIR', __DIR__ . '/cache');
define('GITHUB_CACHE_FILE', GITHUB_CACHE_DIR . '/github-repos.json');
define('GITHUB_CACHE_TTL', 3600);

define('GITHUB_REPO_META', [
    'projeto_cordel' => [
        'name' => 'Projeto Cordel',
        'desc' => 'Página inspirada na literatura de cordel brasileira, com layout narrativo, tipografia expressiva e design que valoriza a cultura popular.',
        'thumb' => 'thumb-cordel',
    ],
    'projeto-android' => [
        'name' => 'Projeto Android',
        'desc' => 'Página informativa sobre o mascote do Android, com estrutura semântica, imagens ilustrativas e design responsivo construído durante o curso de HTML e CSS.',
        'thumb' => 'thumb-android',
    ],
    'projeto-login' => [
        'name' => 'Projeto Login',
        'desc' => 'Tela de login com design moderno e responsivo, explorando formulários, validação visual e boas práticas de interface para autenticação de usuários.',
        'thumb' => 'thumb-login',
    ],
    'projeto-social' => [
        'name' => 'Projeto Social',
        'desc' => 'Página sobre responsabilidade social, com design sensível e conteúdo que destaca causas comunitárias e impacto social positivo.',
        'thumb' => 'thumb-social',
    ],
    'projetos_doces' => [
        'name' => 'Projetos Doces',
        'desc' => 'Página com tema de confeitaria, explorando layout atrativo, tipografia acolhedora e apresentação visual de receitas e produtos.',
        'thumb' => 'thumb-doces',
    ],
    'Php' => [
        'name' => 'Projetos PHP',
        'desc' => 'Exercícios e projetos práticos em PHP, explorando lógica de programação, formulários, sessões e integração com banco de dados.',
        'thumb' => 'thumb-php',
    ],
    'Python' => [
        'name' => 'Projetos Python',
        'desc' => 'Exercícios e projetos em Python, com foco em lógica de programação, automações e análise de dados.',
        'thumb' => 'thumb-python',
    ],
    'html-css' => [
        'name' => 'Curso HTML & CSS',
        'desc' => 'Exercícios e desafios do curso de HTML5 e CSS3, com prática em estruturação semântica, estilização e responsividade.',
        'thumb' => 'thumb-htmlcss',
    ],
    'portifolio' => [
        'name' => 'Portfólio',
        'desc' => 'Este portfólio pessoal — design minimalista com tema claro/escuro, animações sutis e navegação fluida.',
        'thumb' => 'thumb-portfolio',
    ],
]);

define('GITHUB_EXCLUDED_REPOS', ['portifolio']);

function github_fetch_repos(): array
{
    if (!is_dir(GITHUB_CACHE_DIR)) {
        mkdir(GITHUB_CACHE_DIR, 0755, true);
    }

    if (file_exists(GITHUB_CACHE_FILE)) {
        $cache = json_decode(file_get_contents(GITHUB_CACHE_FILE), true);
        if ($cache && isset($cache['timestamp']) && (time() - $cache['timestamp']) < GITHUB_CACHE_TTL) {
            return $cache['repos'];
        }
    }

    $url = 'https://api.github.com/users/' . GITHUB_USERNAME . '/repos?per_page=100&sort=updated&type=owner';
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: Portfolio-Bianca\r\nAccept: application/vnd.github.v3+json\r\n",
            'timeout' => 10,
        ],
    ];
    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        return file_exists(GITHUB_CACHE_FILE)
            ? (json_decode(file_get_contents(GITHUB_CACHE_FILE), true)['repos'] ?? [])
            : [];
    }

    $repos = json_decode($response, true);
    if (!is_array($repos)) {
        return [];
    }

    $projects = github_map_repos($repos);

    file_put_contents(GITHUB_CACHE_FILE, json_encode([
        'timestamp' => time(),
        'repos' => $projects,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    return $projects;
}

function github_map_repos(array $repos): array
{
    $projects = [];

    foreach ($repos as $repo) {
        if (($repo['fork'] ?? false) === true) {
            continue;
        }
        if (in_array($repo['name'], GITHUB_EXCLUDED_REPOS, true)) {
            continue;
        }

        $meta = GITHUB_REPO_META[$repo['name']] ?? null;
        $language = $repo['language'] ?? '';
        $tags = github_extract_tags($language);

        $projects[] = [
            'name' => $meta['name'] ?? github_format_name($repo['name']),
            'desc' => $meta['desc'] ?? ($repo['description'] ?: 'Projeto no GitHub de Bianca Aline.'),
            'tags' => $tags,
            'url' => $repo['homepage'] ?: null,
            'github' => $repo['html_url'],
            'thumb' => $meta['thumb'] ?? ('thumb-' . $repo['name']),
            'stars' => $repo['stargazers_count'] ?? 0,
            'updated' => $repo['pushed_at'] ?? '',
        ];
    }

    usort($projects, function ($a, $b) {
        $aHasPage = !empty($a['url']);
        $bHasPage = !empty($b['url']);
        if ($aHasPage !== $bHasPage) {
            return $aHasPage ? -1 : 1;
        }
        return strcmp($b['updated'], $a['updated']);
    });

    return $projects;
}

function github_extract_tags(string $language): array
{
    $map = [
        'HTML' => ['HTML', 'CSS'],
        'CSS' => ['HTML', 'CSS'],
        'JavaScript' => ['HTML', 'CSS', 'JavaScript'],
        'PHP' => ['PHP', 'HTML', 'CSS'],
        'Python' => ['Python'],
        'Jupyter Notebook' => ['Python', 'Data Science'],
    ];

    return $map[$language] ?? ($language ? [$language] : ['HTML', 'CSS']);
}

function github_format_name(string $name): string
{
    $name = str_replace(['-', '_'], ' ', $name);
    $name = ucwords($name);
    return $name;
}

function get_projects(): array
{
    return github_fetch_repos();
}
