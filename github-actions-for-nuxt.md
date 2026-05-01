# CI/CD para Nuxt com pnpm e Deploy via rsync

Este documento detalha o workflow de Integração Contínua e Entrega Contínua (CI/CD) configurado para este projeto Nuxt.

## Objetivo
O objetivo deste pipeline é automatizar os processos de:
1. **Build e Validação**: Compila a aplicação Nuxt em cada `push` e `pull_request` em qualquer branch para garantir a integridade do código.
2. **Deploy Controlado no Domínio dija.com.br**: Realiza o deploy para o servidor Hostinger via `rsync` **apenas** quando um Pull Request da branch `dev` for fundido (merged) na branch `master`.
3. **Escopo de Produção**: O deploy altera **exclusivamente** o conteúdo da pasta do tema (`wp-content/themes/dija-theme/`), mantendo o core do WordPress e o banco de dados intactos.
4. **Segurança**: Pushes diretos na branch `master` **não** disparam o deploy, garantindo que apenas código revisado via PR chegue à produção.
5. **Resiliência**: Implementa um mecanismo de retry automático para lidar com falhas temporárias de rede com o servidor.

## Git Flow Strategy
Este projeto segue um modelo de ramificação específico:
- **master**: Reflete o código em produção. Deploys automáticos acontecem **estritamente** via merge de PRs vindos da `dev`.
- **dev**: Branch principal de desenvolvimento. Todas as novas funcionalidades e correções devem ser integradas aqui primeiro.
- **Features/Fixes**: Branches temporárias criadas a partir da `dev`.

## O Workflow (`.github/workflows/deploy.yml`)

O pipeline utiliza a lógica de eventos do GitHub Actions para distinguir entre pushes e merges.

### Gatilhos (Triggers)
```yaml
on:
  push:
    branches: [ master, dev ]
  pull_request:
    branches: [ master ]
    types: [ closed ]
```

### Job 1: Build
Este job roda em cada push ou pull request para validar que o código está íntegro.
1. **Instalação**: Executa `pnpm install`.
2. **Lint**: Verifica padrões de código.
3. **Build**: Executa `pnpm run generate` (SSG).
4. **Upload Artifact**: Salva a pasta `.output/public`.

### Job 2: Deploy
Este job possui uma condicional rigorosa para execução:
**Condição**: `if: github.event.pull_request.merged == true && github.event.pull_request.head.ref == 'dev'`

1. **Download Artifact**: Recupera os arquivos gerados no job anterior.
2. **Deploy via rsync**: Sincroniza os arquivos com o servidor Hostinger utilizando SSH.

## Configuração de Secrets
Para que o deploy funcione, as seguintes secrets devem estar configuradas no GitHub:
- `HOSTINGER_SSH_KEY`: Chave privada SSH.
- `HOSTINGER_SERVER_IP`: IP do servidor.
- `HOSTINGER_SSH_PORT`: Porta SSH (Hostinger: 65002).
- `HOSTINGER_USERNAME`: Usuário SSH.
- `HOSTINGER_REMOTE_PATH`: Caminho no servidor (deve ser: `public_html/wp-content/themes/dija-theme/`).

## Como Acionar Manualmente
O deploy também pode ser disparado manualmente através da aba **Actions** no GitHub, selecionando o workflow e clicando em "Run workflow", independente do fluxo de PR.
