---
trigger: always_on
---

# Descrição geral do projeto
## O que é o projeto
- Site da professora de dança de salão Dijaina (Dija) Ferreira, "https://dija.com.br/": turmas, eventos, bio, contato e blog.

## Passo a passo de uso do usuário
1. O usuário chega ao site pelo link https://dija.com.br/
2. O site roda WordPress com um tema próprio em PHP (`wp-theme/`)

## Motivação principal do usuário
- Divulgar turmas e eventos, receber contatos e matrículas, e publicar posts sobre dança de salão.

## Stack
- WordPress (7.1.5 em produção) + tema clássico em PHP (8.5 em produção)
- Sem framework de frontend, sem build, sem plugins obrigatórios
- Ambiente local: Docker (`docker-compose.yml`), WP-CLI via `scripts/wp.sh`

## Conteúdo
- Turmas e Eventos: tipos de conteúdo do tema, com campos fixos no painel
- Textos e fotos fixos: Personalizador ("Conteúdo do site")
- A professora edita tudo no painel; nada de conteúdo fixo no código além dos padrões em `inc/helpers.php`

## Premissas
- Proporcionar uma interface atraente e intuitiva, fiel ao layout aprovado em `design-systems/dija-ferreira-home/`
- Usar apenas APIs nativas e estáveis do WordPress; evitar plugins e customizações do núcleo
- Os scripts relativos a formulários devem ser seguros (sanitizar entrada, escapar saída, nonce, permissões)
- Para alinhamentos de elementos HTML com CSS, sempre usar FLEX, nunca usar GRID
- CSS puro em `wp-theme/assets/styles.css`, com custom properties; fontes com `clamp()`
- Seguir a filosofia DRY: seções reutilizáveis em `template-parts/`, funções de apoio em `inc/helpers.php`
- Usar o idioma inglês para nomear variáveis, constantes e funções (prefixo `dija_` no PHP)
- Usar o idioma português brasileiro para os textos que serão exibidos aos usuários
- O site tem que ser responsivo: smartphones, tablets, notebooks e desktops (até 4K)
- Realizar, sempre que possível, pequenas releases; desenvolver na `dev`, publicar via `master`
- Rodar `php -l` e conferir o layout no navegador (desktop e celular) após mudanças
- As respostas no chat do agente devem sempre ser escritas em português brasileiro.
- **MUITO IMPORTANTE**: O SEO é fundamental para este projeto. HTML semântico (um `<h1>` por página), meta tags adequadas e imagens otimizadas (AVIF com reserva).
