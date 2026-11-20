# Especificação Técnica: Site DIJA (tema WordPress em PHP)

## 1. Resumo do Projeto
- **Objetivo**: Site da professora de dança de salão Dijaina (Dija) Ferreira.
- **Arquitetura**: WordPress tradicional com tema próprio em PHP (`wp-theme/`). O WordPress gera as páginas no servidor; não há frontend separado nem etapa de build.
- **Entrega**: A pasta `wp-theme/` é publicada em `wp-content/themes/dija-theme/`.

## 2. Princípios
- **Sem plugins obrigatórios**: tudo usa APIs nativas e estáveis do WordPress (`register_post_type`, `register_post_meta`, `add_meta_box`, Personalizador, REST API). Atualizações do WordPress não devem exigir mudanças no tema.
- **Conteúdo sem deploy**: turmas, eventos, textos e fotos são editados no painel e aparecem na hora.
- **Layout que não quebra**: campos com formulário fixo (sem editor livre onde o layout é rígido); seções em fluxo (flexbox), com alturas mínimas, para acomodar textos maiores ou mais itens.

## 3. Modelo de conteúdo
| Tipo | Campos | Uso |
|---|---|---|
| `turma` | dia, horario, ritmo, nivel, descricao, cor, icone (+ ordem) | Cards na home e em `/turmas` |
| `evento` | título, descricao, botao_texto, botao_link, imagem destacada (+ ordem) | Carrossel na home e lista em `/eventos` |
| `contact_message` | criado pelo formulário | Menu “Contatos”, com resposta por e-mail |
| Personalizador | textos/fotos de hero, turmas, eventos, sobre, contato, Instagram, WhatsApp | Textos únicos |

Metadados são gravados com prefixo `dija_` e expostos na REST API.

## 4. Padrões de desenvolvimento
- **PHP**: compatível com 8.1+ (produção em 8.5). Escapar toda saída (`esc_html`, `esc_attr`, `esc_url`), sanitizar toda entrada, nonce e `current_user_can` em salvamentos.
- **Nomes**: funções com prefixo `dija_`; código em inglês, textos ao usuário em português brasileiro.
- **CSS**: um arquivo (`assets/styles.css`), custom properties no `:root`, unidades proporcionais ao layout (`--u`), fontes com `clamp()`. **Layout somente com FLEX; GRID é proibido.**
- **Responsivo**: desktop em escala proporcional; abaixo de 768px, layout empilhado com menu hambúrguer.
- **JS**: sem dependências (`assets/app.js`): menu, carrossel e envio do formulário.
- **SEO e acessibilidade**: HTML semântico (um `<h1>` por página), textos alternativos, `aria-*` nos controles, imagens AVIF com reserva.

## 5. Qualidade
- `php -l` em todos os arquivos (roda no CI com PHP 8.5).
- Conferência visual em desktop (1536px, Full HD, 4K) e celular (360–430px) após mudanças de layout.
- Log do PHP vazio em `wp-content/debug.log` (o Docker local liga `WP_DEBUG_LOG`).

## 6. Deploy
- **Hospedagem**: Hostinger (servidor compartilhado), WordPress 7.1.5 + PHP 8.5.
- **Pipeline**: GitHub Actions (`deploy.yml`) — lint de PHP e `rsync --delete` restrito à pasta do tema, em push na `master` ou disparo manual.
- **Git flow**: desenvolvimento na `dev`; produção via merge na `master`.

## 7. Páginas do site
| Rota | Template |
|---|---|
| `/` | `front-page.php` |
| `/turmas` | `archive-turma.php` |
| `/eventos` | `archive-evento.php` |
| `/bio` | `page-bio.php` (página “Bio”) |
| `/contato` | `page-contato.php` (página “Contato”) |
| Notícias (blog), posts e páginas | `index.php`, `single.php`, `page.php` |
| Fotos e vídeos (`/midias`) | a fazer |
