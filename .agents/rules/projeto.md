---
trigger: always_on
---

# Descrição geral do projeto
## O que é o projeto
- Frontend - Esse projeto é um frontend do blog DIJA "https://dija.com.br/" que trará informações sobre a professora de dança de salão Dijaina Ferreira. Úteis para amantes e praticantes de dança de salão.


## Passo a passo de uso do usuário
1. O usuário chegará a esse site através do link https://dija.com.br/
2. O site será um blog rodando wordpress como CMS

## Motivação principal do usuário
- Fazer posts sobre o seu trabalho, dicas, eventos, e outros temas relacionados a dança de salão.

## Framework de desenvolvimento
- Nuxt 

### Módulo principal para os templates
- Nuxt UI

## Backend
- O backend será wordpress
- O frontend enviará os dados através de integração com o Wordpress

## Metodologia de desenvolvimento
- TDD, ou seja, primeiro criar um teste no Vitest e no Cypress, rodá-lo, criar o código principal, rodar novamente o teste e se o teste passar, encerrar a tarefa.

## Premissas
- Proporcionar uma interface atraente e intuitiva
- O visual deve ser moderno e agradável
- Os scripts relativos a formulários devem ser seguros
- Seguir os padrões recomendados pela comunidade Javascript quando for criar nome de variáveis e constantes
- Nunca usar classes Tailwind, salvo onde não houver outra opção
- Para estilos CSS, usar código .SCSS 
- Para alinhamentos de elementos HTML com CSS, sempre usar FLEX, nunca usar GRID 
- Seguir a filosofia DRY "don't repeat yourself" nas sugestões de código e sempre sugerir funções helper ou services para não ter que repetir funções, métodos e variáveis
- Usar o idioma inglês para nomear variáveis, constantes e funções
- Usar o idioma português brasileiro para os textos que serão exibidos aos usuários.
- O site tem que ser responsivo e ficar com visualização adaptada para smartphones, tablets, notebooks e desktops
- Realizar, sempre que possível, pequenas releases
- Trabalhar com integração contínua
- A medida que o projeto for crescendo, refatorar o código quando necessário, e quando refatorar, refazer o teste correspondente à refatoração e rodar o teste para ter certeza que tudo continua funcionando.
- Separar partes do código em componentes para que possam ser reutilizados.
- O layout deve utilizar os componentes e scripts do módulo Nuxt UI https://ui.nuxt.com/
- Ao usar `vee-validate`, deve-se obrigatoriamente utilizar a função `defineField` do `useForm` para gerar referências mutáveis compatíveis com `v-model`, evitando erros de "readonly" em `form.values`.
- As respostas no chat do agente devem sempre ser escritas em português brasileiro.
- **MUITO IMPORTANTE**: O SEO é fundamental para este projeto. O layout, os componentes e as páginas devem ser estruturados seguindo as melhores práticas de SEO (uso semântico de tags HTML, meta tags adequadas, otimização de imagens, etc.).