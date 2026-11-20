---
trigger: always_on
---
# Token Optimization & Project Cache Rule

Para economizar tokens, **nunca explore o projeto inteiro e não leia arquivos inteiros**.
Sempre consulte os arquivos em `.agents/memory/` antes:
- `.agents/memory/01-architecture.md` (Estrutura do tema WordPress)
- `.agents/memory/02-conventions.md` (Padrões de nomenclatura, CSS e PHP)
- `.agents/memory/03-hot-paths.md` (Arquivos críticos)
- `.agents/memory/04-tech-decisions.md` (Decisões técnicas)

Utilize `grep_search` e leia apenas as linhas de interesse com `view_file` especificando `StartLine` e `EndLine`.
