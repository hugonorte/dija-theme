# DESIGN.md

## Resumo Executivo
- Esse projeto é um frontend do blog DIJA "https://dija.com.br/" que trará informações sobre a professora de dança de salão Dijaina Ferreira. Úteis para amantes da dança de salão

## 🌟 Atmosfera e Princípios de Design

O **Dija** deve transmitir uma sensação de **"Dança Premium"**. Isso significa uma combinação de elegância e precisão de um dançarino.
- **Confiança e Elegância**: Interface limpa e dados bem estruturados.
- **Modernidade**: Uso de glassmorphism, sombras suaves e animações fluidas.
- **Limpeza de Código**: Separação clara entre estrutura (HTML) e estilo (SCSS), evitando a poluição do template com classes utilitárias excessivas.

---

## 🎨 Paleta de Cores

### Cores Primárias (Rosa/Roxo)
Utilizamos uma escala de rosa/roxo que remete ao tema de dança:
- **Primary-500**: `#ff389d` (Base)
- **Primary-600**: `#e61e8a` (Destaque principal)
- **Primary-700**: `#cc1a7a` (Hover e estados ativos)

### Cores Neutras e Superfície
- **Background**: `#ffffff` (White)
- **Surface**: `rgba(255, 255, 255, 0.8)` (Branco translúcido com blur)
- **Text Primary**: `#333333` (Cinza Escuro - Leitura confortável)
- **Text Secondary**: `#666666` (Cinza Médio - Legendas e hints)

---

## Typography ✍️

- **Principal**: [Montserrat](https://fonts.google.com/specimen/Montserrat)
- **Cabeçalhos**: Montserrat (Black/Bold para impacto tecnológico)
- **Hierarquia**:
  - `H1`: 3rem (48px), Black, Tracking Tight.
  - `Body`: 1rem (16px), Regular.
  - `Label`: 0.875rem (14px), Medium.

---

## 📐 Espaçamento e Layout

### Sistema de Grid
- Layout responsivo baseado em colunas do Tailwind/Nuxt UI.
- Desktop: Sidebar de navegação (1/4) e Área de conteúdo (3/4).
- Mobile: Coluna única com Stepper horizontal no topo.

### Bordas e Sombras
- **Border Radius**: `20px` para cards principais (Premium look).
- **Shadows**: `0 10px 15px -3px rgba(0, 0, 0, 0.1)` (Profundidade suave).

---

## 🧩 Componentes Customizados

### Premium Card
- **Estilo**: Glassmorphism (`backdrop-filter: blur(12px)`).
- **Interação**: Elevação suave no hover (`translateY(-4px)`).
- **Uso**: Container principal de cada passo do Wizard.


---

##  Metodologia de Estilização

### SCSS (Prioridade Total)
- A estilização deve ser feita via arquivos SCSS (ou blocos `<style lang="scss">` scoped nos componentes). 
- Manter o HTML semântico e legível.
- Facilitar a reutilização de variáveis e mixins.

### Tailwind CSS (Exceções)
O uso de Tailwind é restrito a:
- Ajustes pontuais e excepcionais.
- Customização de propriedades internas de componentes do **Nuxt UI** através do atributo `ui`.
- Prototipagem rápida que será posteriormente refatorada para SCSS.

---

## 🛠️ Regras para IA (Stitch Context)
Ao gerar novas telas ou componentes:
1. Sempre utilize componentes do **Nuxt UI** como base.
2. **Priorize SCSS** para qualquer customização visual.
3. Utilize Tailwind apenas para props de componentes UI ou em casos de extrema simplicidade.
4. Aplique a classe `.premium-card` para containers de dados.
5. Mantenha os textos em **Português Brasileiro** e o código em **Inglês**.
