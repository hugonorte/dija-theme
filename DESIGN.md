# Guia de Design: Blog DIJA

## 1. Atmosfera e Princípios
O **Blog DIJA** deve transmitir a elegância, o movimento e a precisão da dança de salão. A interface deve ser "Premium", moderna e fluida.
- **Elegância**: Espaçamento generoso e tipografia sofisticada.
- **Fluidez**: Micro-animações e transições suaves entre páginas.
- **Limpeza**: Código HTML semântico, com estilo centralizado em SCSS.

---

## 2. Identidade Visual

### 2.1. Paleta de Cores (Dance Vibrance)
- **Primary (Rosa/Roxo)**: 
  - `Base`: `#ff389d`
  - `Deep`: `#e61e8a`
  - `Dark`: `#cc1a7a`
- **Neutras**:
  - `Background`: `#ffffff`
  - `Text Primary`: `#333333` (Cinza antracite para leitura)
  - `Text Muted`: `#666666`
- **Especiais**:
  - `Glass`: `rgba(255, 255, 255, 0.8)` com `backdrop-filter: blur(12px)`.

### 2.2. Tipografia
- **Família Principal**: [Montserrat](https://fonts.google.com/specimen/Montserrat).
- **H1/Títulos**: Montserrat Bold/Black com `tracking-tight`.
- **Corpo**: Montserrat Regular para legibilidade.

---

## 3. Layout e Componentes

### 3.1. Sistema de Layout
- **Flexbox Obrigatório**: Todo o alinhamento do projeto deve usar Flexbox. O uso de CSS Grid é proibido por diretriz de projeto.
- **Responsividade**: Mobile-first. Layout de coluna única em smartphones e grade flexível em desktops.

### 3.2. Elementos "Premium"
- **Bordas**: `border-radius: 20px` para elementos de destaque.
- **Sombras**: Sombras suaves (`0 10px 15px -3px rgba(0, 0, 0, 0.1)`) para profundidade.
- **Cards**: Efeito Glassmorphism em seções de destaque.

---

## 4. Metodologia de Estilização (Diretriz Técnica)
- **Prioridade SCSS**: 90% do estilo deve estar em blocos SCSS ou arquivos externos.
- **Tailwind CSS**: Uso restrito a propriedades do Nuxt UI (atributo `ui`) ou ajustes pontuais de margem/padding quando o SCSS for desnecessariamente complexo.
- **Nomenclatura**: Variáveis e classes em Inglês; Textos de interface em Português-BR.

---

## 5. Regras para Agentes IA
Ao criar novos componentes:
1. Use componentes do **Nuxt UI v4** como base.
2. Aplique **Flexbox** para todo e qualquer alinhamento.
3. Não utilize classes utilitárias excessivas no HTML; use SCSS.
4. Garanta que o estado de carregamento (Skeleton/Loader) seja elegante.
