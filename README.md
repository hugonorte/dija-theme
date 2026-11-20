# Dija Ferreira — Site e Tema WordPress

Site da professora de dança de salão **Dija Ferreira** ([dija.com.br](https://dija.com.br/)):
turmas, eventos, bio, contato e blog. Tema WordPress feito sob medida, em PHP,
sem etapa de build e sem plugins obrigatórios.

![WordPress](https://img.shields.io/badge/WordPress-7.1.5-21759B?logo=wordpress&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![CSS](https://img.shields.io/badge/CSS-663399?logo=css&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-sem_dependências-F7DF1E?logo=javascript&logoColor=black)
![Docker](https://img.shields.io/badge/Docker-ambiente_local-2496ED?logo=docker&logoColor=white)
![GitHub Actions](https://img.shields.io/badge/GitHub_Actions-CI%2FCD-2088FF?logo=githubactions&logoColor=white)
![Hostinger](https://img.shields.io/badge/Hostinger-hospedagem-673DE6?logo=hostinger&logoColor=white)

---

## Sumário

1. [Stack e tecnologias](#1-stack-e-tecnologias)
2. [Manual de uso (painel do WordPress)](#2-manual-de-uso-painel-do-wordpress)
3. [Instruções ao desenvolvedor](#3-instruções-ao-desenvolvedor)
   - [3.1 Ambiente de desenvolvimento](#31-ambiente-de-desenvolvimento-passo-a-passo)
   - [3.2 Estrutura do código](#32-estrutura-do-código)
   - [3.3 CI/CD](#33-cicd)
   - [3.4 Trocar o layout no futuro](#34-se-um-dia-o-layout-for-trocado-por-completo)
4. [Licença](#4-licença)

---

## 1. Stack e tecnologias

| Camada | Tecnologia | Observação |
|---|---|---|
| CMS | WordPress 7.1.5 | Versão igual à da produção |
| Tema | PHP 8.5 | Tema clássico em `wp-theme/`; só APIs nativas do WordPress |
| Banco | MySQL 8.0 (local) | Na Hostinger, o banco da hospedagem |
| Front-end | HTML, CSS e JavaScript puros | Um CSS, um JS; sem frameworks e sem build |
| Fontes | Google Fonts (Oswald e Roboto Condensed) | |
| Imagens | AVIF com reserva em JPG/PNG | |
| Ambiente local | Docker Compose + WP-CLI | Versões fixadas |
| CI/CD | GitHub Actions + rsync via SSH | Deploy só da pasta do tema |
| Hospedagem | Hostinger (servidor compartilhado) | |

---

## 2. Manual de uso (painel do WordPress)

Tudo o que aparece no site é editado no painel (`/wp-admin`). O conteúdo
salvo aparece **na hora**, sem precisar de programador ou de deploy.

O site tem três lugares de edição:

| Onde | O que se edita |
|---|---|
| Menu **Turmas** | Os cards de turmas (home e página `/turmas`) |
| Menu **Eventos** | Os eventos (carrossel da home e página `/eventos`) |
| **Aparência → Personalizar → Conteúdo do site** | Textos fixos e fotos de cada seção |

> No Personalizador, a tela da direita mostra a prévia do site. Nada vai ao ar
> até clicar em **Publicar**. Se um campo for apagado, o site volta a mostrar
> o texto padrão daquele campo.

### 2.1 Topo do site (nome, menu e botão)

**Aparência → Personalizar → Conteúdo do site → Topo e contatos**

- **Nome no topo**: o texto à esquerda (ex.: DIJA FERREIRA).
- **Botão do topo — texto / link**: o botão laranja (ex.: MATRICULE-SE → `/contato/`).
- **Instagram** e **WhatsApp**: usados na seção de contato. Digite o
  `@usuario` e o número com DDD; o site monta os links sozinho.

Os links do menu (INÍCIO, TURMAS, EVENTOS, SOBRE) já vêm prontos. Para
mudá-los, crie um menu em **Aparência → Menus**, marque o local
**“Menu principal (topo)”** e salve. Para voltar ao padrão, desmarque o local.

### 2.2 Destaque da página inicial (nome, frase e foto)

**Aparência → Personalizar → Conteúdo do site → Início (destaque)**

| Campo | Onde aparece |
|---|---|
| Nome — 1ª e 2ª linha | “Dija” (roxo) e “Ferreira” (vermelho) |
| Faixa roxa | “PROFESSORA DE DANÇA DE SALÃO” (até ~32 caracteres) |
| Frase | “Venha dançar com / a gente e ser feliz”. **Cada linha do campo é uma linha no site** |
| Botão — texto / link | “QUERO DANÇAR!”. O link padrão é a página `/turmas/` |
| Foto | A foto grande à direita |

**Foto do destaque:** use PNG ou AVIF **com fundo transparente** (só a
pessoa recortada), em formato retrato (~3:4) e com pelo menos 1000 px de
largura. O rosto deve ficar no terço de cima. No celular, aparece só da
cintura para cima.

### 2.3 Turmas e locais

As turmas aparecem em dois lugares:

- **Página inicial:** cards coloridos com as turmas **em andamento**. No pé
  de cada card aparecem o nome e o endereço do local e, abaixo do card, o
  botão **“Quero participar”** (WhatsApp com a turma, o local, o dia e o horário).
- **Página `/turmas`**, de cima para baixo:
  1. **Destaque (topo):** título e **carrossel** com as imagens de divulgação
     das próximas turmas. Clicar numa imagem leva à seção “Próximas turmas”.
  2. **Dois botões grandes**, “Turmas em andamento” e “Próximas turmas”, que
     rolam a página até cada seção.
  3. **Turmas em andamento** e 4. **Próximas turmas**: agrupadas por
     **local** (nome e endereço) e, dentro do local, por **dia da semana**.
     Cada card tem o botão **“Quero participar”**, que abre o WhatsApp com
     uma mensagem pronta contendo a turma, o local, o dia e o horário.

#### Passo 1 — Cadastrar os locais

Menu **Turmas → Locais**. No formulário à esquerda, preencha e clique em
**Adicionar novo local**:

| Campo | Exemplo | Observação |
|---|---|---|
| Nome | Studio Centro | Título do bloco do local |
| Endereço | Rua X, 100 — Boa Vista, Recife/PE | Vira um link para o Google Maps |
| Ordem | 1 | Locais com número menor aparecem primeiro |

Para **editar** um local, clique no nome dele na lista à direita. A mudança
vale para todas as turmas do local. Para **excluir**, use o link
**Excluir**: as turmas daquele local passam a aparecer em “Local a definir”.

#### Passo 2 — Cadastrar as turmas

Menu **Turmas → Adicionar turma**:

| Campo | Exemplo | Observação |
|---|---|---|
| Situação | Em andamento / Próxima turma | Define a seção da página `/turmas`. Só “Em andamento” aparece na página inicial |
| Dia da semana | Segunda | Lista de Segunda a Domingo |
| Horário | 19h às 20h | Comece pela hora de início: turmas do mesmo dia são ordenadas por ela |
| Ritmo / estilo de dança | FORRÓ | Até 16 caracteres |
| Nível | INICIANTE | Até 16 caracteres |
| Início (só próximas turmas) | Início em 05/01 | Opcional. Aparece em destaque no card |
| Cor do card | Vermelho, Verde-azulado, Roxo ou Amarelo-ocre | |
| Ícone | Sol claro, Lua com estrelas, Estrela ou Sol escuro | |

Na lateral da tela:

- **Locais:** escolha **um** local (ou “Nenhum”, se ainda não definido).
- **Imagem de divulgação (16:9):** para próximas turmas, envie a arte de
  divulgação. Toda próxima turma **com imagem** entra no carrossel do topo da
  página `/turmas`; com 2 ou mais, aparecem setas e pontinhos, e no celular
  dá para deslizar o dedo. Sem nenhuma imagem, o topo mostra só o título. Use **1920×1080 px** (ou outra medida 16:9), com
  os textos importantes longe das bordas.
- **Atributos → Ordem:** desempate entre turmas do mesmo dia e horário.

Clique em **Publicar**. O título da turma no painel é criado sozinho a
partir dos campos (ex.: “SEGUNDA · 19h às 20h · FORRÓ INICIANTE”).

#### Atualizar, mudar de situação ou remover

- **Atualizar:** **Turmas → Todas as turmas**, clique na turma, altere e
  clique em **Atualizar**. A lista mostra situação, local e imagem de cada
  turma.
- **Uma próxima turma começou:** abra a turma e mude a **Situação** para
  **Em andamento**. Ela sai do carrossel e passa para “Turmas em andamento”
  (e para a página inicial). A imagem de divulgação pode ficar salva; ela só
  é usada em próximas turmas.
- **Tirar do site:** passe o mouse sobre a turma na lista e clique em
  **Lixeira**. Para esconder por um tempo sem apagar, mude o status para
  **Rascunho**.

#### Textos das seções

**Personalizar → Conteúdo do site → Turmas**: títulos “NOSSAS / TURMAS”,
texto de abertura de `/turmas`, títulos “Turmas em andamento” e “Próximas
turmas” (usados também nos dois botões), o texto das próximas turmas, os
avisos exibidos quando uma seção está vazia, o texto do botão dos cards e a
**mensagem do WhatsApp**.

Na mensagem do WhatsApp, use os marcadores abaixo; o site troca cada um pelos
dados da turma clicada:

| Marcador | Vira |
|---|---|
| `{turma}` | Ritmo e nível (ex.: FORRÓ INICIANTE) |
| `{local}` | Nome do local |
| `{endereco}` | Endereço do local |
| `{dia}` | Dia da semana |
| `{horario}` | Horário |
| `{inicio}` | Campo “Início” (próximas turmas) |

O número que recebe as mensagens é o **WhatsApp** de **Topo e contatos**
(seção 2.1). Sem número cadastrado, o botão leva à página de contato.

Não há limite de turmas nem de locais: os cards quebram de linha sozinhos
(no celular, 2 por linha).

### 2.4 Eventos (carrossel)

**Criar um evento:** menu **Eventos → Adicionar evento**:

| Campo | Exemplo | Observação |
|---|---|---|
| Título (topo da tela) | LEVAMOS A DANÇA ATÉ VOCÊ! | A chamada em amarelo |
| Descrição | Apresentações culturais, festivais… | Até 220 caracteres |
| Texto do botão | SAIBA MAIS | |
| Link do botão | `/eventos/` | Padrão: a página de eventos. Aceita endereço completo ou caminho do site |
| **Imagem destacada** (caixa lateral) | A ilustração | Opcional. Sem ela, aparece a arte dos músicos |

O campo **Ordem** (caixa Atributos) define a ordem dos slides. Com **2 ou
mais eventos**, o carrossel da home ganha as setas de navegação. Todos os
eventos também aparecem, em lista, na página `/eventos`.

**Botão do evento:** na página inicial, o botão leva ao **Link do botão**
(padrão: `/eventos/`). Na página `/eventos`, o botão abre o **WhatsApp** com
uma mensagem pronta citando o evento, editável em **Personalizar → Eventos →
mensagem do botão** (use `{evento}` para o título do evento).

**Ilustração:** imagem horizontal (~3:2), de preferência com fundo
transparente ou escuro, para combinar com a seção preta.

- **Título da seção** (“EVENTOS”) e **texto de abertura** da página `/eventos`: **Personalizar → Conteúdo do site → Eventos**.
- Atualizar ou remover segue o mesmo caminho das turmas.

#### Galeria de fotos (fim da página `/eventos`)

A seção **Fotos** mostra as fotos dos eventos de que vocês participaram,
sempre **da mais recente para a mais antiga**, cada uma com um selo de mês e
ano (ex.: JUN 2026). O visitante pode filtrar por **ano**, **evento** e
**tag** e clicar numa foto para vê-la ampliada. A seção só aparece quando há
ao menos uma foto.

**Passo 1 — Eventos das fotos (uma vez por evento):** **Eventos → Eventos das
fotos**, digite o nome (ex.: “São João de Caruaru 2026”) e clique em
**Adicionar**. É o nome usado no filtro “Evento” e na legenda da foto.

**Passo 2 — Adicionar cada foto:** **Eventos → Fotos → Adicionar foto**:

| Onde | O que fazer |
|---|---|
| Caixa **Foto** (lateral) | **Enviar ou escolher a foto**: envie do computador ou escolha na Biblioteca de Mídia. No campo “Texto alternativo”, descreva a cena (acessibilidade e Google) |
| **Mês e ano do evento** | Ex.: junho de 2026. Define o selo e a ordem. Vazio = mês atual |
| Caixa **Eventos das fotos** | Escolha **um** evento |
| Caixa **Tags das fotos** | Digite palavras separadas por vírgula (ex.: `forró, casamento, Recife`) e clique em **Adicionar**; as já usadas aparecem como sugestão |

Clique em **Publicar**. O título no painel é criado sozinho (ex.: “São João
de Caruaru · 06/2026”).

- **Lista de fotos:** **Eventos → Fotos da galeria** mostra miniatura, evento,
  mês/ano e tags; clique em “Mês/ano” para inverter a ordem.
- **Remover:** **Lixeira** na lista. A imagem continua na Biblioteca de Mídia
  (apague-a lá, se quiser liberar espaço).
- **Renomear um evento ou uma tag:** **Eventos → Eventos das fotos** ou
  **Tags das fotos**; a mudança vale para todas as fotos.
- **Textos da seção** (título, parágrafo e aviso sem fotos):
  **Personalizar → Conteúdo do site → Eventos**.
- A galeria mostra **24 fotos por página**; acima disso aparecem os números
  das páginas.

### 2.5 Sobre a professora

**Na página inicial:** **Personalizar → Conteúdo do site → Sobre a professora**

| Campo | Onde aparece |
|---|---|
| Título pequeno / Título grande | “SOBRE A / PROFESSORA” |
| Parágrafo | O texto curto da home (até ~260 caracteres) |
| Destaque roxo | “AQUI, VOCÊ NÃO APRENDE / APENAS A DANÇAR.” (cada linha do campo é uma linha) |
| Destaque final | “VOCÊ VIVE A CULTURA!” |
| Botão (página inicial) — texto / link | “SAIBA MAIS”, levando à página `/bio/` (o botão não aparece na própria `/bio`) |
| Foto | Foto quase quadrada; o rosto no terço de cima |

**Página completa (`/bio`):** **Páginas → Bio**. O texto completo é escrito
no editor da página. A **Imagem destacada** da página, se houver, substitui
a foto nessa página. Os títulos e destaques são os mesmos do Personalizador.

> A página precisa se chamar **Bio** e ter o endereço (slug) **`bio`**. É o
> endereço que o tema usa para aplicar o layout especial.

### 2.6 Contato e mensagens recebidas

**Textos:** **Personalizar → Conteúdo do site → Contato**: título, texto
(cada linha do campo é uma linha), texto do botão e a mensagem exibida após
o envio. Instagram e WhatsApp ficam em **Topo e contatos** (seção 2.1).

**Mensagens recebidas:** cada envio legítimo do formulário é **enviado por
e-mail para contato@dija.com.br** (endereço editável em **Personalizar →
Contato → E-mail que recebe as mensagens**) e também fica salvo no menu
**Contatos**, com nome, e-mail, WhatsApp e mensagem. Basta clicar em
“Responder” no programa de e-mail: a resposta vai direto para quem escreveu.
Se o e-mail falhar, a mensagem continua salva no painel. Ao abrir uma mensagem, a
caixa lateral **“Responder Mensagem”** permite:

- **responder por e-mail** direto do painel (exige o e-mail SMTP configurado);
- **abrir a conversa no WhatsApp** com um clique.

O formulário tem três proteções contra robôs, sem plugins:

1. **Campo-armadilha (honeypot):** um campo invisível para pessoas, que robôs
   costumam preencher. Nesse caso o envio é descartado em silêncio (o robô
   recebe “sucesso” e não tenta de novo) e o IP fica **bloqueado no
   formulário por 72 horas**. O site continua acessível para ele.
2. **Armadilha de tempo:** envios feitos menos de 3 segundos depois de a
   página abrir são descartados (pessoas levam mais tempo para preencher).
3. **Limite de envios:** no máximo 5 mensagens por hora do mesmo IP. A pessoa
   vê o aviso “Muitas mensagens em pouco tempo…”.

Os IPs bloqueados aparecem em **Contatos → IPs bloqueados**, com a data de
expiração e o botão **Desbloquear**. O bloqueio some sozinho ao expirar.

A página `/contato` mostra só esta seção e precisa ter o endereço (slug)
**`contato`** em **Páginas**.

### 2.7 Blog (notícias), páginas e ícone do site

- **Posts:** menu **Posts → Adicionar post**. Para criar uma página
  “Notícias” com a lista de posts, crie a página e escolha-a em
  **Configurações → Leitura → Página de posts**.
- **Outras páginas** (ex.: Política de privacidade): menu **Páginas**.
  Elas usam o layout padrão de texto do site.
- **Ícone da aba do navegador:** **Aparência → Personalizar → Identidade do site → Ícone do site**.

---

## 3. Instruções ao desenvolvedor

### 3.1 Ambiente de desenvolvimento (passo a passo)

**Pré-requisitos:** Git e Docker (com Docker Compose v2). Não é preciso ter
PHP, Node ou MySQL instalados.

**1. Clonar o repositório e entrar na branch de desenvolvimento**

```bash
git clone git@github.com:hugonorte/dija-theme.git
cd dija-theme
git checkout dev
```

**2. (Opcional) Configurar o e-mail.** Para testar o envio de respostas aos
contatos, copie o modelo e preencha os dados SMTP:

```bash
cp .env.example .env
```

**3. Subir os containers**

```bash
docker compose up -d
```

Sobem três containers: o MySQL, o WordPress e um serviço rápido
(`uploads-perms`) que só acerta a permissão da pasta de uploads e encerra.
O WordPress fica em **http://localhost:8080** e o MySQL na porta 3306
(usuário `wordpress`, senha `wordpress_password`, banco `wordpress`).
O tema é a pasta `wp-theme/`, montada direto no container: **toda alteração
de código aparece ao recarregar a página**.

**4. Instalar o WordPress (só na primeira vez, com o banco vazio)**

```bash
sh scripts/wp.sh core install --url=http://localhost:8080 \
  --title="Dija Ferreira" --admin_user=admin \
  --admin_password='escolha-uma-senha' --admin_email=voce@example.com
sh scripts/wp.sh language core install pt_BR --activate
sh scripts/wp.sh theme activate dija-theme
```

`scripts/wp.sh` roda o [WP-CLI](https://wp-cli.org/) em um container
temporário. Funciona com qualquer comando: `sh scripts/wp.sh plugin list`,
`sh scripts/wp.sh post list --post_type=turma` etc.

**5. Criar o conteúdo de exemplo** (as páginas Bio e Contato, 2 locais,
4 turmas em andamento, 2 próximas turmas com imagem de divulgação, 1 evento
e 8 fotos na galeria; o script não duplica o que já existe):

```bash
sh scripts/seed-dev.sh
```

**6. Acessar o painel:** http://localhost:8080/wp-admin

**Comandos úteis**

| Tarefa | Comando |
|---|---|
| Ver o log de erros do PHP | `docker exec dija_wordpress cat /var/www/html/wp-content/debug.log` |
| Checar a sintaxe do PHP (igual ao CI) | `docker run --rm -v "$PWD/wp-theme:/t:ro" php:8.5-cli sh -c 'find /t -name "*.php" -exec php -l {} \;'` |
| Parar o ambiente | `docker compose down` (os dados continuam salvos) |
| Apagar tudo, inclusive o banco | `docker compose down -v` |

**Atualizar as versões do WordPress ou do PHP.** As versões ficam fixas
no `docker-compose.yml` e devem ser **iguais às da produção**. Para mudar,
troque a tag da imagem e rode:

```bash
docker compose up -d --force-recreate --renew-anon-volumes wordpress
sh scripts/wp.sh core update-db
```

> O `--renew-anon-volumes` é necessário porque a imagem oficial guarda os
> arquivos do WordPress em um volume anônimo; sem ele, a versão antiga
> continua no container. O banco, os uploads e o tema não são afetados.

No ambiente local, a atualização automática do WordPress fica desligada e o
log de erros (`WP_DEBUG_LOG`) fica ligado.

### 3.2 Estrutura do código

```
wp-theme/                     → vira wp-content/themes/dija-theme/
├── style.css                 Cabeçalho do tema (nome, versão, licença)
├── functions.php             Carrega os módulos de inc/
├── inc/
│   ├── helpers.php           Textos padrão (dija_defaults), dija_mod(), imagens e links
│   ├── setup.php             Suportes do tema, CSS/JS, itens do menu, favicon
│   ├── content-types.php     Tipos “turma” e “evento”, locais, campos e telas de edição
│   ├── gallery.php           Galeria de fotos: tipo “foto”, eventos/tags das fotos e filtros
│   ├── customizer.php        Painel “Conteúdo do site” do Personalizador
│   ├── form-security.php     Anti-robô: honeypot, armadilha de tempo, limite e bloqueio temporário
│   └── contact.php           API do formulário, tipo “contact_message” e SMTP
├── template-parts/           Uma seção por arquivo (hero, turmas, eventos, sobre, contato)
├── front-page.php            Página inicial
├── archive-turma.php         /turmas
├── archive-evento.php        /eventos
├── page-bio.php              /bio
├── page-contato.php          /contato
├── page.php · single.php · index.php · 404.php
└── assets/
    ├── styles.css            Todo o CSS (desktop, depois o bloco do celular)
    ├── app.js                Menu hambúrguer, carrossel e envio do formulário
    ├── img/                  Imagens (AVIF + reserva)
    └── icons/                Fontes SVG dos ícones e máscaras (embutidos no CSS)
scripts/
├── wp.sh                     WP-CLI no Docker
└── seed-dev.sh               Conteúdo de exemplo
design-systems/               Protótipo HTML aprovado (fora do git)
```

**Regras do projeto:**

- Layout **somente com flexbox; GRID é proibido**. As seções ficam em fluxo,
  com altura mínima, para acomodar textos maiores e mais itens.
- No CSS, `--u` equivale a 1 px da largura de referência (1536 px). As fontes
  usam `clamp()`, e abaixo de 768 px o layout empilha, com menu hambúrguer.
- No PHP, as funções levam o prefixo `dija_`. Toda saída é escapada
  (`esc_html`, `esc_attr`, `esc_url`) e toda entrada é sanitizada; os
  salvamentos verificam nonce e permissão.
- Código em inglês; textos para o usuário em português do Brasil.
- Um `<h1>` por página (SEO). As seções recebem `page => true` para usar
  `<h1>` na própria página.
- Os ícones SVG ficam embutidos no CSS (data URI). Depois de editar um
  arquivo em `assets/icons/`, atualize a variável `--icon-*` ou `--mask-*`
  correspondente no topo de `styles.css`.

### 3.3 CI/CD

Há dois workflows em `.github/workflows/`:

**`deploy.yml`**, com dois jobs:

| Job | Quando roda | O que faz |
|---|---|---|
| `check` | Push em `master` ou `dev`, **PR aberto/atualizado para a `master`** e disparo manual | `php -l` (PHP 8.5) em todo o tema, `node --check` no `app.js`, confere os arquivos obrigatórios e barra o uso de GRID no CSS |
| `deploy` | Push na `master` (ex.: merge do PR) ou disparo manual, só se o `check` passar | Envia `wp-theme/` por `rsync --delete` para `public_html/wp-content/themes/dija-theme/` e roda o **teste de fumaça** |

- **Um deploy por vez:** um novo deploy espera o anterior terminar.
- **Teste de fumaça:** depois do envio, abre `/`, `/turmas/` e `/eventos/` no
  site de produção e **falha** se alguma página não responder, se ainda
  servir o HTML antigo do Nuxt ou se não estiver usando o tema `dija-theme`.

**`cleanup-nuxt.yml`** (uso único, só manual): limpa os restos do Nuxt na raiz
do `public_html` (ver checklist abaixo). Por padrão é uma **simulação**, que só
lista o que seria apagado; para apagar, rode de novo marcando **executar**. Só
remove itens que comprovadamente vieram do Nuxt (HTML com `/_nuxt/` e a pasta
`_nuxt`) e nunca toca em `wp-admin`, `wp-content`, `wp-includes`, `wp-*.php`,
`index.php` ou `.htaccess`. Pastas com outros tipos de arquivo são ignoradas,
com aviso.

**Fluxo de trabalho (git flow):** desenvolva na `dev` (ou numa branch criada
a partir dela), abra um PR para a `master` e, ao fazer o merge, o deploy
roda sozinho.

**Secrets necessários no GitHub** (Settings → Secrets and variables → Actions):

| Secret | Valor |
|---|---|
| `HOSTINGER_SSH_KEY` | Chave privada SSH |
| `HOSTINGER_SERVER_IP` | IP do servidor |
| `HOSTINGER_SSH_PORT` | Porta SSH (Hostinger: 65002) |
| `HOSTINGER_USERNAME` | Usuário SSH |
| `HOSTINGER_REMOTE_PATH` | `public_html` |
| `HOSTINGER_KNOWN_HOSTS` | **Recomendado.** Impressão digital do servidor SSH (ver abaixo). Sem ele, o deploy funciona, mas avisa que não verifica a identidade do servidor |

Variável opcional (aba *Variables*): `SITE_URL`, endereço usado no teste de
fumaça (padrão `https://dija.com.br`).

**Como gerar o `HOSTINGER_KNOWN_HOSTS`:** num computador de confiança, rode
`ssh-keyscan -p 65002 IP_DO_SERVIDOR`, confira a impressão digital com a
exibida no hPanel da Hostinger (SSH Access) e cole a saída inteira no secret.
Com ele, o deploy recusa conectar se o servidor “mudar de identidade” (sinal de
ataque *man-in-the-middle*).

**O que saber sobre o deploy:**

- **Só a pasta do tema é tocada.** O `--delete` deixa a pasta do tema no
  servidor idêntica a `wp-theme/` (arquivos removidos no repositório também
  somem lá), mas nada fora dela é alterado: núcleo do WordPress, plugins,
  uploads e banco ficam intactos.
- **Conteúdo não depende de deploy.** Turmas, eventos e textos ficam no banco
  de produção; o deploy só é necessário quando o código muda.
- **Não há build:** o que está no repositório é exatamente o que vai ao ar.
- **Proxy/CDN (ex.: Cloudflare):** o tema usa o IP real da conexão e ignora
  cabeçalhos como `X-Forwarded-For`, que o visitante pode forjar. Se o site
  passar a ficar atrás de um proxy, defina no `wp-config.php` o cabeçalho
  confiável, por exemplo
  `define( 'DIJA_TRUSTED_IP_HEADER', 'HTTP_CF_CONNECTING_IP' );`; sem isso,
  todos os visitantes pareceriam ter o IP do proxy.
- **SMTP na produção:** defina no `wp-config.php` as constantes `SMTP_HOST`,
  `SMTP_PORT`, `SMTP_USER`, `SMTP_PASS` e `SMTP_FROM`. O tema lê as constantes
  primeiro e, se não existirem, as variáveis de ambiente.
- **Versões:** se a Hostinger atualizar o WordPress ou o PHP, atualize também
  o `docker-compose.yml` e o `php-version` do workflow.

**Checklist do primeiro deploy deste tema** (migração do site antigo em Nuxt):

1. Apagar da raiz do `public_html` os arquivos estáticos antigos do Nuxt
   (`index.html`, `_nuxt/` e as pastas `turmas/`, `eventos/`, `bio/`,
   `contato/` etc.). **Se ficarem, o servidor os entrega no lugar do WordPress.**
   Use o workflow **Actions → “Limpar restos do Nuxt”**: rode primeiro sem
   marcar nada (simulação), confira a lista e rode de novo com **executar**.
   Faça isso logo após o primeiro deploy do tema; o teste de fumaça desse
   deploy deve falhar até a limpeza, e passar ao rodá-lo de novo
   (Actions → Deploy → *Re-run*).
2. Criar as páginas **Bio** (slug `bio`) e **Contato** (slug `contato`).
3. Abrir **Configurações → Links permanentes** e clicar em **Salvar**, para
   ativar os endereços `/turmas` e `/eventos`.
4. Cadastrar turmas, eventos e textos (seção 2).
5. Se usar o cache do LiteSpeed (comum na Hostinger), limpe o cache após o
   primeiro deploy, para não servir páginas antigas.

### 3.4 Se um dia o layout for trocado por completo

O tema junta duas coisas: o **visual** (templates, CSS e imagens) e a
**estrutura de dados** (tipos de conteúdo, campos e opções). Dá para trocar
o visual à vontade, desde que a estrutura de dados seja preservada ou migrada.

#### Front-end (visual)

- **Pode trocar livremente:** `template-parts/`, os templates da raiz,
  `assets/styles.css`, `assets/img/` e `assets/icons/`.
- **O layout atual é protegido por direitos autorais** (ver `LICENSE`). Um
  layout novo deve ser criação própria; não reaproveite CSS, imagens ou
  ícones atuais em outro projeto sem autorização.
- **Mantenha os ganchos do JavaScript** ou atualize o `app.js` junto:
  - menu: `.nav`, `.nav-toggle`, `.nav-links` e a classe `is-open`;
  - carrossel: `[data-carousel]`, `.slide`, `.is-active`, `.carousel-prev` e `.carousel-next`;
  - formulário: `[data-contact-form]`, `data-endpoint`, `data-success` e `.form-status`.
- **Formulário de contato:** mantenha os campos `name`, `email`, `whatsapp`
  e `message` e o campo-armadilha oculto `fax_number`, que bloqueia robôs. Sem
  ele, a proteção contra spam para de funcionar.
- **Textos de tamanho variável:** a professora pode escrever mais do que o
  layout previu. Prefira seções em fluxo, com altura mínima, a posições
  absolutas.
- **SEO e acessibilidade:** um `<h1>` por página, imagens com `alt` e
  hierarquia de títulos coerente.
- **Proporções das fotos:** se o novo layout pedir outros formatos, oriente
  a professora a reenviar as imagens no tamanho certo.

#### Back-end (dados)

Estes nomes estão gravados no banco de produção. **Mudá-los sem migração faz
o conteúdo “sumir” do site**, embora ele continue no banco:

| Item | Nomes atuais | Onde é definido |
|---|---|---|
| Tipos de conteúdo | `turma`, `evento`, `foto`, `contact_message` | `inc/content-types.php`, `inc/gallery.php`, `inc/contact.php` |
| Campos (post meta) | turma: `dija_status`, `dija_dia`, `dija_horario`, `dija_ritmo`, `dija_nivel`, `dija_inicio`, `dija_cor`, `dija_icone` (+ imagem destacada = divulgação); evento: `dija_descricao`, `dija_botao_texto`, `dija_botao_link` | `dija_fields()` |
| Proteção do formulário | opção `dija_blocked_ips` (bloqueios com expiração), transients `dija_rate_*`, campos `hp_campo_vazio` e `form_ts` (carimbo assinado) | `inc/form-security.php` |
| Galeria de fotos | tipo `foto` (meta `dija_data` = AAAA-MM; imagem destacada = foto); taxonomias `foto_evento` (opção única) e `foto_tag`; filtros na URL `foto-ano`, `foto-evento`, `foto-tag`, `fotos-pagina` | `inc/gallery.php` |
| Locais (taxonomia) | `local`, com os campos `dija_endereco` e `dija_ordem` (term meta) | `register_taxonomy()` em `inc/content-types.php` |
| Valores das listas | situação `andamento`/`proxima`; dias `seg`…`dom`; cores `red`/`teal`/`purple`/`ochre`; ícones `sun`/`moon`/`star-big`/`sun2` | `dija_turma_status()`, `dija_weekdays()`, `dija_turma_colors()`, `dija_turma_icons()` |
| Textos do Personalizador | `dija_*` (ex.: `dija_hero_name_1`) | `inc/customizer.php`, `dija_defaults()` |
| Endereços | `/turmas`, `/eventos`; páginas com slug `bio` e `contato` | `rewrite` dos tipos; `page-bio.php`, `page-contato.php` |
| API do formulário | `POST /wp-json/dija/v1/contact` | `inc/contact.php` |

**Pontos de atenção:**

1. **Os tipos de conteúdo vivem no tema.** Se o tema for **trocado por
   outro**, “Turmas”, “Eventos” e “Contatos” somem do painel e do site até
   que o novo tema registre os mesmos tipos. Antes da troca, mova
   `inc/content-types.php` e `inc/contact.php` para um plugin próprio
   (`wp-content/mu-plugins/` é a opção mais simples) e tire-os do tema; assim
   os dados ficam independentes do visual.
2. **Os textos do Personalizador pertencem ao tema.** O WordPress os guarda
   na opção `theme_mods_dija-theme`, ligada ao **nome da pasta**. Um tema novo
   com outra pasta começa com os textos vazios. Mantenha a pasta
   `dija-theme` ou copie a opção antes:
   `sh scripts/wp.sh option get theme_mods_dija-theme --format=json`.
3. **Renomear ou remover campos exige migração.** Exemplo, renomeando um
   campo em todas as turmas:
   `sh scripts/wp.sh db query "UPDATE wp_postmeta SET meta_key='dija_novo' WHERE meta_key='dija_antigo'"`.
   Faça backup do banco antes (`sh scripts/wp.sh db export backup.sql`).
4. **Mudou os endereços?** Salve os links permanentes depois do deploy e
   crie redirecionamentos 301 dos endereços antigos, para não perder
   posicionamento no Google.
5. **Ensaie a troca no ambiente local** com uma cópia do banco de produção
   (exportada no hPanel da Hostinger) antes de publicar.

---

## 4. Licença

Este repositório tem **duas licenças diferentes** (detalhes e fundamentos
legais em [`LICENSE`](LICENSE)):

- **Layout, CSS, imagens e ícones:** © 2026 Hugo Norte, todos os direitos
  reservados. **É proibido copiar** (Lei nº 9.610/1998).
- **Código PHP e JavaScript:** [GNU GPL v2 ou posterior](COPYING), a mesma
  licença do WordPress. Pode ser copiado e modificado, desde que os avisos de
  autoria sejam mantidos, com menção à origem
  (`github.com/hugonorte/dija-theme`).
