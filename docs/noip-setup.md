# Configuração No-IP para CBAV

## Configuração Concluída ✅

O projeto CBAV foi configurado para usar o domínio No-IP `vertexsolutions.ddns.net`.

### Configurações Aplicadas

1. **URL da Aplicação**: `https://vertexsolutions.ddns.net`
2. **Cookies Seguros**: Habilitados para HTTPS
3. **Domínios Autorizados**: `vertexsolutions.ddns.net`
4. **Ambiente**: Produção

### Credenciais No-IP

- **Hostname**: `vertexsolutions.ddns.net`
- **Username/Email**: `6ddde8x`
- **DDNS Key**: [Sua senha única]
- **Hostname para dispositivos**: `all.ddnskey.com`

## Próximos Passos

### 1. Configurar o Cliente No-IP (DUC)

1. Baixe o DUC (Dynamic Update Client) do No-IP
2. Instale e execute o programa
3. Faça login com as credenciais:
   - **Username**: `6ddde8x`
   - **Password**: [Sua senha única]
4. Selecione o hostname: `vertexsolutions.ddns.net`
5. Configure para atualizar automaticamente

### 2. Configurar Porta Forwarding

No seu roteador, configure o port forwarding:
- **Porta Externa**: 80 (HTTP) e 443 (HTTPS)
- **Porta Interna**: 80 (HTTP) e 443 (HTTPS)
- **IP Interno**: IP do seu servidor local
- **Protocolo**: TCP

### 3. Configurar SSL/HTTPS

Para usar HTTPS, você precisará:
1. Instalar um certificado SSL (Let's Encrypt recomendado)
2. Configurar o Apache/Nginx para HTTPS
3. Configurar redirecionamento HTTP → HTTPS

### 4. Testar a Configuração

1. Acesse: `https://vertexsolutions.ddns.net`
2. Verifique se o site carrega corretamente
3. Teste as funcionalidades principais

## Configurações do Sistema

### Arquivo .env Atualizado

```env
APP_URL=https://vertexsolutions.ddns.net
SESSION_SECURE_COOKIE=true
SANCTUM_STATEFUL_DOMAINS=vertexsolutions.ddns.net
APP_ENV=production
```

### Comandos Executados

```bash
# Limpar cache de configuração
php artisan config:clear

# Limpar cache da aplicação
php artisan cache:clear
```

## Troubleshooting

### Se o site não carregar:
1. Verifique se o DUC está rodando
2. Confirme se o port forwarding está configurado
3. Teste se o IP está sendo atualizado no No-IP

### Se HTTPS não funcionar:
1. Verifique se o certificado SSL está instalado
2. Confirme se o Apache/Nginx está configurado para HTTPS
3. Teste com HTTP primeiro: `http://vertexsolutions.ddns.net`

### Se houver problemas de sessão:
1. Verifique se `SESSION_SECURE_COOKIE=true`
2. Confirme se o domínio está correto em `SANCTUM_STATEFUL_DOMAINS`
3. Limpe o cache: `php artisan config:clear`

## Suporte

Para problemas técnicos, consulte:
- Documentação No-IP: https://www.noip.com/support
- Documentação Laravel: https://laravel.com/docs
- Logs do sistema: `storage/logs/laravel.log` 