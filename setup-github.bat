@echo off
echo ========================================
echo   CBAV CRM - Setup GitHub Repository
echo   Vertex Solutions LTDA
echo ========================================
echo.

echo Configurando repositório GitHub...
echo.

echo Adicionando repositório remoto...
git remote add origin https://github.com/SEU_USUARIO/cbav-crm-ministerial.git

echo.
echo Renomeando branch para main...
git branch -M main

echo.
echo Fazendo push do projeto completo...
git push -u origin main

echo.
echo ========================================
echo   Repositório configurado com sucesso!
echo ========================================
echo.
echo Próximos passos:
echo 1. Adicione o Jules como colaborador
echo 2. Configure permissões de force push
echo 3. Teste o acesso
echo.
echo Para o Jules fazer atualizações:
echo git add .
echo git commit -m "Atualizacao do sistema"
echo git push --force-with-lease origin main
echo.
pause
