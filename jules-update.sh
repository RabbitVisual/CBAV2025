#!/bin/bash

echo "========================================"
echo "   CBAV CRM - Atualizacao Jules Google"
echo "   Vertex Solutions LTDA"
echo "========================================"
echo

echo "Verificando status do repositorio..."
git status

echo
echo "Adicionando todas as mudancas..."
git add .

echo
echo "Digite uma descricao das melhorias implementadas:"
read -p "Descricao: " descricao

echo
echo "Fazendo commit das atualizacoes..."
git commit -m "Atualizacao CBAV: $descricao

- Melhorias implementadas por Jules do Google
- Sistema otimizado e atualizado
- Funcionalidades aprimoradas

Desenvolvido por Jules do Google
Data: $(date)"

echo
echo "Fazendo push para o GitHub..."
git push --force-with-lease origin main

echo
echo "========================================"
echo "   Atualizacao concluida com sucesso!"
echo "========================================"
echo
echo "Repositorio: https://github.com/RabbitVisual/CBAV2025"
echo
