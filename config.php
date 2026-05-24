<?php

return [
    'site' => [
        'name' => 'Bianca Aline — Desenvolvedora Web',
        'url' => 'https://bianca-aline.dev',
        'title' => 'Bianca Aline — Desenvolvedora Web | Portfólio e CV',
        'description' => 'Portfolio e currículo digital de Bianca Aline — desenvolvedora web com projetos elegantes, automações em IA e soluções centradas no usuário.',
        'og_description' => 'Desenvolvedora web com projetos elegantes, automações em IA e soluções centradas no usuário. Conheça meu trabalho.',
        'locale' => 'pt_BR',
        'theme_color' => '#7c3aed',
        'language' => 'pt-br',
    ],

    'person' => [
        'name' => 'Bianca Aline',
        'full_name' => 'Bianca Aline da S. Negretti',
        'given_name' => 'Bianca',
        'family_name' => 'Negretti',
        'job_title' => 'Desenvolvedora Web',
        'eyebrow' => 'Desenvolvedora em formação',
        'email' => 'bianca.alinedev@gmail.com',
        'city' => 'Sorocaba, SP',
        'focus' => 'Web · IA · Python · SQL',
        'cv_file' => 'curriculo.pdf',
        'cv_download_name' => 'Bianca-Aline-CV.pdf',
    ],

    'social' => [
        [
            'label' => 'GitHub',
            'url' => 'https://github.com/BiancaAlinesv',
            'aria' => 'Perfil de Bianca Aline no GitHub',
        ],
        [
            'label' => 'LinkedIn',
            'url' => 'https://www.linkedin.com/in/bianca-aline',
            'aria' => 'Perfil de Bianca Aline no LinkedIn',
        ],
        [
            'label' => 'Email',
            'url' => 'mailto:bianca.alinedev@gmail.com',
            'aria' => 'Enviar email para Bianca Aline',
        ],
    ],

    'skills' => [
        ['name' => 'HTML5', 'level' => 80],
        ['name' => 'CSS3', 'level' => 80],
        ['name' => 'JavaScript', 'level' => 70],
        ['name' => 'Python', 'level' => 65],
        ['name' => 'SQL', 'level' => 60],
        ['name' => 'IA / Inteligência Artificial', 'level' => 75],
    ],

    'projects' => [
        [
            'name' => 'Landing page de alto impacto',
            'desc' => 'Design responsivo e foco em conversão, com navegação rápida, tipografia refinada e apresentação profissional de marca pessoal.',
            'tags' => ['HTML', 'CSS', 'JavaScript'],
            'url' => '#',
            'github' => '#',
            'thumb' => 'thumb-1',
        ],
        [
            'name' => 'Fundação digital pronta para crescer',
            'desc' => 'Estrutura pensada para expansão, integrando conteúdo, desempenho e usabilidade com experiência moderna desde o primeiro acesso.',
            'tags' => ['Python', 'SQL', 'UX'],
            'url' => null,
            'github' => null,
            'thumb' => 'thumb-2',
        ],
    ],

    'nav' => [
        ['label' => 'Início', 'href' => '#home', 'num' => '00'],
        ['label' => 'Sobre', 'href' => '#about', 'num' => '01'],
        ['label' => 'Projetos', 'href' => '#projects', 'num' => '02'],
        ['label' => 'Habilidades', 'href' => '#skills', 'num' => '03'],
        ['label' => 'Contato', 'href' => '#contact', 'num' => '04'],
    ],
];
