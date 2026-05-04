# Especificação Técnica: Blog DIJA (Nuxt 4 + WordPress)

## 1. Resumo do Projeto
- **Objetivo**: Frontend moderno e performático para o blog da professora Dijaina Ferreira.
- **Arquitetura**: Headless CMS (WordPress como backend, Nuxt como frontend estático).
- **Entrega**: O build final é injetado como um tema WordPress no diretório `wp-content/themes/dija-theme/`.

---

## 2. Metodologia de Qualidade (Diretrizes para @qa e @engineer)

### 2.1. Workflow Gatekeeper (Pré-Push)
Antes de qualquer submissão ao repositório, o código deve obrigatoriamente passar no comando:
```bash
pnpm check-all
```
Este comando executa sequencialmente:
1. **Lint**: Verificação de estilo e boas práticas (ESLint).
2. **Typecheck**: Verificação de tipos estáticos (TypeScript).
3. **Generate**: Build completo (SSG) para validar a integridade da compilação.

### 2.2. Estratégia de Testes (TDD)
- **Unitários**: Vitest para composables e lógica de helpers.
- **E2E**: Cypress para fluxos de navegação e integração com a API do WP.
- **Regra**: O teste deve ser concebido junto ou antes da funcionalidade.

### 2.3. Padrões de Estabilidade E2E (Aprendizados de Campo)
Para garantir que os testes do Cypress passem de forma consistente no ambiente Nuxt, as seguintes regras devem ser seguidas em formulários e elementos interativos:

1.  **Isolamento de Hidratação (`<ClientOnly>`)**: No Nuxt 4, o processo de hidratação pode "resetar" o DOM e destruir listeners de evento enquanto o Cypress já está interagindo. **Sempre envolva formulários críticos em `<ClientOnly>`** para garantir que a interação ocorra apenas no DOM estável do lado do cliente.
2.  **Visibilidade Determinística (`v-show` vs `v-if`)**: Para elementos que o Cypress precisa validar a presença (como mensagens de erro), prefira `v-show`. Isso mantém o elemento no DOM o tempo todo, evitando erros de "elemento não encontrado" durante transições rápidas de estado.
3.  **Seletores Padrão (`name` e `id`)**: Nunca remova os atributos `name` e `id` dos inputs. Testes E2E frequentemente dependem de seletores como `input[name="xxx"]` para localizar campos de forma robusta.
4.  **Botões e Submissão**: Utilize `<button type="submit">` dentro de um `<form @submit.prevent="...">`. Esta é a forma mais estável para o Cypress disparar eventos de submissão e interceptar requisições XHR/Fetch.
5.  **Reatividade Síncrona**: No handler de submissão, as atualizações de estado da UI (como exibir erros de validação) devem ocorrer de forma síncrona antes de chamadas assíncronas (await). Isso garante que o Cypress detecte as mudanças na interface sem timeouts.

---

## 3. Padrões de Desenvolvimento (@engineer)

### 3.1. TypeScript e Segurança de Dados
- **Null Safety**: Serviços e Composables devem retornar `null` (e não `undefined`) para representar dados não encontrados.
- **Exemplo**: `Promise<WordPressPost | null>`.
- **Tratamento de Rotas**: Parâmetros de rota (`route.params`) devem ser tratados com valores padrão (ex: `|| ''`) para evitar erros de tipo `string | undefined`.
- **Proibição do `any`**: Tipagem estrita é obrigatória. Use interfaces como `WordPressPost`, `WordPressPage`, etc.

### 3.2. Estilo e Interface
- **Layout**: Uso obrigatório de **FLEX**. O uso de GRID é proibido por regra de projeto.
- **Estilização**: Priorizar arquivos `.scss` para manter o template Vue limpo.
- **Nuxt UI v4**: Componentes devem seguir o padrão da v4, utilizando o wrapper `<UApp>` no `app.vue`.

### 3.3. Exceções de Linting Aceitáveis
- **v-html**: Permitido apenas para renderizar o conteúdo bruto do WordPress. Deve ser silenciado localmente no topo do bloco `<template>` com:
  `<!-- eslint-disable vue/no-v-html -->`.
- **Nuxt Config Order**: A regra de ordem de chaves está desabilitada no `nuxt.config.ts` para evitar conflitos lógicos do plugin.

---

## 4. Arquitetura de Deploy
- **Ambiente**: Hostinger (Servidor Compartilhado).
- **Pipeline**: GitHub Actions (`deploy.yml`) dispara o build e envia os arquivos via `rsync`.
- **Arquivos Obrigatórios**: `index.php` e `style.css` (metadados do tema) devem estar na pasta `public/` para garantir o reconhecimento do tema pelo WordPress.

---

## 5. Tecnologias Principais
- **Frontend**: Nuxt 4, Nuxt UI v4, Tailwind CSS v4.
- **Linguagem**: TypeScript.
- **Backend**: WordPress REST API.
- **Ferramentas**: pnpm, Husky, ESLint, Vitest, Cypress.
