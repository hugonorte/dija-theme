---
trigger: always_on
---
# Token Optimization Strategies (Resumo)

Consulte `.agents/memory/` (arquitetura, convenções, arquivos críticos e decisões) antes de explorar o código.
Regras principais:
1. Sempre verifique o cache (`.agents/memory/`) primeiro.
2. Não leia arquivos completos; use `grep_search` e leia blocos usando as linhas Iniciais/Finais em `view_file`.
3. Use ferramentas de substituição locais (como `replace_file_content`) em vez de recriar arquivos inteiros.
4. Antes de mexer com dependências, olhe o `package.json`.
5. Utilize flags `--silent` ou `--quiet` nos testes no terminal.
