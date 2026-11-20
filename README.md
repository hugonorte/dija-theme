# Site Dija Ferreira — tema WordPress

Site da professora de dança de salão Dija Ferreira (https://dija.com.br/).
Tema WordPress clássico em PHP, sem build e sem plugins obrigatórios.

## Estrutura

```
wp-theme/                 Tema (vai para wp-content/themes/dija-theme/)
├── functions.php         Carrega os módulos de inc/
├── inc/
│   ├── helpers.php       Textos padrão, imagens, links
│   ├── setup.php         Suportes, CSS/JS, menu, favicon
│   ├── content-types.php Turmas e Eventos (campos fixos no painel)
│   ├── customizer.php    Textos/fotos em Aparência > Personalizar
│   └── contact.php       Formulário (REST dija/v1/contact), mensagens, SMTP
├── template-parts/       Seções reutilizáveis (hero, turmas, eventos, sobre, contato)
├── front-page.php        Página inicial
├── archive-turma.php     /turmas
├── archive-evento.php    /eventos
├── page-bio.php          /bio
├── page-contato.php      /contato
├── page.php, single.php, index.php, 404.php
└── assets/               styles.css, app.js, img/, icons/ (fontes SVG)
scripts/
├── wp.sh                 WP-CLI no Docker (ex.: sh scripts/wp.sh plugin list)
└── seed-dev.sh           Conteúdo inicial do ambiente local
design-systems/           Protótipo HTML aprovado (referência visual; fora do git)
```

## Ambiente local

Requisitos: Docker.

```bash
docker compose up -d          # http://localhost:8080
sh scripts/seed-dev.sh        # páginas Bio/Contato, 4 turmas e 1 evento
```

As versões são fixas e iguais às da produção (WordPress 7.1.5 + PHP 8.5).
Ao mudar a tag em `docker-compose.yml`, recrie o container com os arquivos
do núcleo novos:

```bash
docker compose up -d --force-recreate --renew-anon-volumes wordpress
sh scripts/wp.sh core update-db
```

Variáveis de SMTP (opcionais) vêm do `.env`: `SMTP_HOST`, `SMTP_PORT`,
`SMTP_USER`, `SMTP_PASS`, `SMTP_FROM`.

## Onde cada conteúdo é editado

| Conteúdo | Painel |
|---|---|
| Turmas (cards) | menu **Turmas** — campo “Ordem” define a posição |
| Eventos (carrossel e /eventos) | menu **Eventos** — ilustração = imagem destacada |
| Textos fixos e fotos | **Aparência → Personalizar → Conteúdo do site** |
| Bio completa | página **Bio** (endereço `bio`) |
| Mensagens do formulário | menu **Contatos** |
| Menu do topo (opcional) | **Aparência → Menus**, local “Menu principal” |

## Deploy

`.github/workflows/deploy.yml`: em push na `master` (ou manual), verifica a
sintaxe do PHP e envia `wp-theme/` por `rsync --delete` para
`public_html/wp-content/themes/dija-theme/` na Hostinger. Secrets:
`HOSTINGER_SSH_KEY`, `HOSTINGER_SERVER_IP`, `HOSTINGER_SSH_PORT`,
`HOSTINGER_USERNAME`, `HOSTINGER_REMOTE_PATH`.

Conteúdo editado no painel aparece na hora; deploy só é necessário quando o
código do tema muda.
