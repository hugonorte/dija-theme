#!/bin/bash

echo "🔍 Iniciando verificações pré-push..."

# 1. Linting
echo "🎨 Verificando Lint..."
pnpm lint
if [ $? -ne 0 ]; then
  echo "❌ Erro no Lint! Por favor, corrija antes de subir."
  exit 1
fi

# 2. Typecheck
echo "⌨️ Verificando Types..."
pnpm typecheck
if [ $? -ne 0 ]; then
  echo "❌ Erro de TypeScript! Por favor, corrija antes de subir."
  exit 1
fi

# 3. Build/Generate
echo "📦 Simulando Build (Generate)..."
pnpm run generate
if [ $? -ne 0 ]; then
  echo "❌ Erro no Build! O projeto não está gerando corretamente."
  exit 1
fi

echo "✅ Todas as verificações passaram! Pode subir o código."
exit 0
