# Especificação Técnica: Blog DIJA - Wordpress + Nuxt

## Resumo Executivo
- Esse projeto é um frontend do blog DIJA "https://dija.com.br/" que trará informações sobre a professora de dança de salão Dijaina Ferreira. Úteis para amantes e praticantes de dança de salão.
- O blog utilizará o wordpress como backend para gerenciamento de conteúdo, e o Nuxt como frontend para consumo da API REST.
- O frontend consumirá a API REST do wordpress para obter os posts do blog.

### Arquitetura de Deploy (Headless via Theme)
Para manter o fluxo profissional e desacoplado, você deve usar dois ambientes:

**A. Ambiente Local (Docker)**
- Use um `docker-compose.yml` que sobe o WordPress (Core) e um banco de dados em containers.
- O repositório do Nuxt será mapeado/montado como um "Volume" diretamente dentro da pasta `wp-content/themes/dija-theme/`.
- Quando o WordPress atualiza a versão (ex: 6.4 para 6.5), o container do Core é atualizado sem tocar no código do nosso tema.

**B. O Fluxo de Git (CI/CD)**
- O repositório Git armazenará **apenas o código-fonte** Nuxt/Vue, nunca os arquivos compilados (pastas de build estarão no `.gitignore`).
- Ao fazer um `git push` para a branch principal (main), uma pipeline de CI/CD (ex: Github Actions) será disparada.
- A pipeline executa o comando de build (ex: `pnpm generate` para gerar uma SPA/SSG).
- A pipeline então envia (via FTP/SSH ou Rsync) os arquivos finais da pasta `.output/public` para a pasta do tema no servidor de produção, junto com os arquivos mínimos exigidos pelo WP (`index.php` e `style.css`).


## Metodologia e Garantia de Qualidade (QA)

### Testes E2E (Cypress)
O projeto utiliza Cypress para garantir a integridade dos fluxos críticos.


### Metodologia de Desenvolvimento
- **TDD (Test Driven Development)**: Sempre que possível, criar o teste antes da funcionalidade.
- **Filosofia DRY**: Uso extensivo de composables (ex: `useWizardState`) para evitar duplicação de lógica.
- **Responsividade**: Mobile-first, garantindo que o Wizard seja utilizável em smartphones no campo.

---
- Docker 
- Docker compose
- Nuxt 4 (Versão atualizada)
- Nuxt UI v4 (Configurado com Tailwind v4)
- pnpm
- TypeScript
- SCSS (Estilização primária)
- tailwindcss v4
- vee-validate
- zod
- Gerenciamento de estado do frontend com composables
- Repositório de código - Github (poderá ser acessado através de um server MCP)
- Hospedagem: Hostinger (Servidor Compartilhado)
- PHP (Rodando em um container Docker)
- Wordpress (Rodando em um container Docker)

### Arquitetura resumida:
- Frontend (Nuxt)
   ↓
- API (Laravel Controller)
   ↓
-Service (Nutrition Engine)
   ↓
-Cálculo (PHP / Solver)
   ↓
-Resposta JSON
   ↓
-Frontend renderiza

## Requisitos Funcionais



## Requisitos Não-Funcionais
- **Hospedagem**: Build SSG (`pnpm run generate`) compatível com Hostinger (via FTP).
- **Responsividade**: Totalmente adaptado para Mobile, Tablet e Desktop.
- **Metodologia**: Uso rigoroso de TDD (Test Driven Development) e filosofia DRY.
- **Idioma**: Interface em Português-BR, código (variáveis/funções) em Inglês.


## Gerenciamento de Estado
O fluxo de dados será centralizado em composables nativos do Nuxt (`app/composables/`), permitindo que as informações do candidato persistam durante a navegação entre a confirmação do plano e o formulário final.

## Segurança e Validação
- **VeeValidate + Zod**: Obrigatório para todos os formulários. O schema Zod deve validar:
    - Cadastro: Nome (mínimo 5, apenas caracteres alfabéticos e espaços), Email (formato), Nascimento (data válida), Celular (exatamente 10 dígitos: 2 de DDD + 8 do telefone).
    - Comprovante: Tipo de arquivo (mimetype) e tamanho máximo.
- **Validação Imediata**: As mensagens de erro do `vee-validate` e `zod` devem ser exibidas imediatamente abaixo do input correspondente em tempo real (on input).
- **v-model com VeeValidate**: Para evitar erros de "readonly" em objetos reativos do vee-validate (como `form.values`), deve-se utilizar obrigatoriamente a função `defineField` do `useForm` para gerar referências mutáveis compatíveis com `v-model`.
- **Restrição de Mídia**: O sistema não deve gerar arquivos de imagem, vídeo ou áudio. Deve apenas referenciar as imagens estáticas existentes em `app/assets/img/`.
- **Proteção contra Bots**: Sugestão de Cloudflare Turnstile para prevenir cadastros automatizados.
- **LGPD**: Checkbox obrigatório de consentimento.
- **Sanitização**: Limpeza de inputs antes do envio à API.

## Gerenciador de pacotes
- pnpm

## Backend
- O backend será wordpress


## Metodologia de desenvolvimento
- TDD, ou seja, primeiro criar um teste, rodá-lo, criar o código principal, rodar novamente o teste e se o teste passar, encerrar a tarefa.

## Estilos CSS e Framework UI
- Nunca usar classes Tailwind, salvo onde não houver outra opção
- Para estilos CSS, usar código .SCSS 
- Para alinhamentos de elementos HTML com CSS, sempre usar FLEX, nunca usar GRID 
- **Nuxt UI v4**: Base para componentes acessíveis. Utiliza o novo motor do Tailwind v4.
- **SCSS (Primário)**: A estilização do projeto será definida majoritariamente e primeiramente em SCSS. O objetivo é manter o código HTML limpo, separando a estrutura do estilo para garantir maior organização e facilidade de manutenção (Filosofia de separação de interesses).
- **Tailwind CSS v4**: Será utilizado apenas em momentos excepcionais e focado em ajustes finos em elementos específicos do módulo Nuxt UI. Deve-se evitar o uso excessivo de classes utilitárias no HTML.

## Configurações Críticas de UI (Padrão Nuxt UI v4)
Para garantir que os estilos sejam aplicados corretamente e sigam o padrão do projeto em caso de reinicialização:

1. **Importação Obrigatória no CSS Global**:
   - No arquivo de entrada CSS (ex: `app/assets/css/app.css`), é obrigatório incluir:
     ```css
     @import "tailwindcss";
     @import "@nuxt/ui";
     ```
   - Sem a importação do `@nuxt/ui`, os componentes não carregarão seus estilos base.

2. **Configuração de Tema (`app.config.ts`)**:
   - O tema deve ser definido no `app.config.ts` para alinhar com a identidade Agro-Premium:
     ```typescript
     export default defineAppConfig({
       ui: {
         primary: 'green',
         gray: 'slate'
       }
     })
     ```

3. **Wrapper Global (`app.vue`)**:
   - Todos os componentes devem estar dentro do wrapper `<UApp>` para que os estilos e feedbacks visuais funcionem corretamente.

4. **Padrão de Propriedades de Componentes (v4)**:
   - **UStepper**: Utilizar a propriedade `title` para o título de cada etapa (não use `label`, que foi depreciado ou alterado na v4).
   - **Icons**: Utilizar o prefixo `i-` (ex: `i-heroicons-leaf`).

## Plano de Verificação
- Verificação visual em múltiplos breakpoints.
- Simulação de erros de API para garantir mensagens amigáveis ao usuário.
### Nuxt UI v4 Implementation Patterns

### Nuxt UI v4 Implementation Patterns (TanStack Table)

#### UTable Requirements
Nuxt UI v4 `UTable` is powered by TanStack Table. To ensure correct data mapping and reactivity:
- **Column Definitions**: Use `accessorKey` for data mapping and `header` for the column label. 
- **Slot Pattern**: Customizing cells requires the `#<accessorKey>-cell` slot. The slot scope provides a `cell` object; use `cell.getValue()` to access the data.
- **SSR/Hydration**: Always wrap `UTable` in `<ClientOnly>` when dealing with client-side state (like Wizard state) to prevent hydration mismatches.
- **Empty State**: Use the `#empty` slot to handle cases with no data gracefully.

Example:
```typescript
const columns = [
    { accessorKey: 'nome', header: 'Ingrediente' },
    { accessorKey: 'ms', header: 'MS (%)' }
]
```

Slot Example:
```vue
<template #nome-cell="{ cell }">
  <span>{{ cell.getValue() }}</span>
</template>
```
