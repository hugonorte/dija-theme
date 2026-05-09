---
trigger: always_on
---
# Token Optimization Strategies (Resumo)

Consulte `.agents/TOKEN_OPTIMIZATION_QUICK_START.md` e `.agents/README_TOKEN_OPTIMIZATION.md` para estratégias completas.
Regras principais:
1. Sempre verifique o cache (`.agents/memory/`) primeiro.
2. Não leia arquivos completos; use `grep_search` e leia blocos usando as linhas Iniciais/Finais em `view_file`.
3. Use ferramentas de substituição locais (como `replace_file_content`) em vez de recriar arquivos inteiros.
4. Antes de mexer com dependências, olhe o `package.json`.
5. Utilize flags `--silent` ou `--quiet` nos testes no terminal.
