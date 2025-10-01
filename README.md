# 🏛️ CBAV CRM Ministerial

**Sistema completo de gestão ministerial para a Congregação Batista Avenida**

Desenvolvido pela **Vertex Solutions** - CEO Reinan Rodrigues

---

## 🚀 **Sistema 100% Funcional e Completo**

### ✅ **Tecnologias Implementadas:**
- **Laravel 12** com PHP 8.2+
- **MySQL** configurado e otimizado
- **Blade + Tailwind CSS** para frontend responsivo
- **Livewire** para componentes interativos
- **Laravel Sanctum** para autenticação segura
- **Spatie Laravel Permission** para controle de acesso
- **Gateways de Pagamento** (Stripe, Mercado Pago, PIX)

### 🎯 **Funcionalidades Principais:**

#### 👥 **Gestão de Membros**
- ✅ CRUD completo de membros
- ✅ Upload de fotos
- ✅ Vínculo a ministérios e cargos
- ✅ Dados pessoais e ministeriais
- ✅ Histórico de participação

#### 🏛️ **Gestão Ministerial**
- ✅ Ministérios com cores e ícones
- ✅ Departamentos hierárquicos
- ✅ Cargos e responsabilidades
- ✅ Estrutura organizacional completa

#### 💰 **Controle Financeiro**
- ✅ Transações (dízimos, ofertas, campanhas, saídas)
- ✅ Campanhas de arrecadação
- ✅ Progresso visual das campanhas
- ✅ Upload de comprovantes

#### 🔐 **Sistema de Segurança**
- ✅ 4 níveis de acesso (Super Admin, Pastor, Tesoureiro, Líder)
- ✅ Permissões granulares
- ✅ Middleware de proteção
- ✅ Autenticação segura

#### 📊 **Dashboard e Relatórios**
- ✅ Dashboard com estatísticas em tempo real
- ✅ Cards informativos
- ✅ Gráficos de progresso
- ✅ Dados recentes

---

## 🛠️ **Instalação e Configuração**

### **Pré-requisitos:**
- PHP 8.2+
- MySQL 5.7+
- Composer
- Node.js 16+ (para compilação de assets)

### **Métodos de Instalação:**

#### **🚀 Método 1: Instalador Web (Recomendado para Hospedagem)**

1. **Faça upload dos arquivos** para seu servidor
2. **Acesse:** `https://seudominio.com/install.php`
3. **Siga o assistente** de instalação
4. **Configure** banco de dados e super admin
5. **Pronto!** Sistema instalado automaticamente

#### **💻 Método 2: Instalação Manual (Desenvolvimento)**

```bash
# 1. Clone o repositório
git clone [url-do-repositorio]
cd CBAV

# 2. Instale as dependências
composer install

# 3. Configure o ambiente
cp .env.example .env
php artisan key:generate

# 4. Configure o banco de dados no .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cbav
DB_USERNAME=root
DB_PASSWORD=

# 5. Execute a instalação automática
php artisan cbav:install

# 6. Inicie o servidor
php artisan serve
```

#### **🔧 Método 3: Instalação via Comando**

```bash
# Instalação completa via comando
php artisan cbav:install

# Verificação do sistema
php artisan cbav:check

# Verificação com correções automáticas
php artisan cbav:check --fix
```

---

## 🔑 **Acesso ao Sistema**

### **URL:** `http://localhost:8000` (desenvolvimento) ou `https://seudominio.com` (produção)

### **Credenciais:**
- **Email:** Definido durante a instalação
- **Senha:** Definida durante a instalação

---

## 🛠️ **Comandos Úteis**

### **Instalação e Configuração:**
```bash
# Instalação completa
php artisan cbav:install

# Verificação do sistema
php artisan cbav:check

# Verificação com correções
php artisan cbav:check --fix

# Backup do sistema
php artisan backup:sistema

# Limpeza de cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### **Manutenção:**
```bash
# Verificar status
php artisan about

# Listar rotas
php artisan route:list

# Verificar logs
tail -f storage/logs/laravel.log

# Resetar permissões
php artisan permission:cache-reset
```

### **Níveis de Acesso:**

#### 👑 **Super Administrador**
- Acesso total ao sistema
- Gestão de usuários e permissões
- Configurações do sistema

#### 🏛️ **Pastor**
- Gestão de membros
- Gestão de ministérios
- Visualização de relatórios
- Gestão de campanhas

#### 💰 **Tesoureiro**
- Gestão de transações
- Relatórios financeiros
- Gestão de campanhas
- Upload de comprovantes

#### 👥 **Líder**
- Visualização de membros
- Gestão de cargos
- Relatórios básicos

---

## 📱 **Interface Profissional**

### **Design Responsivo:**
- ✅ Mobile-first design
- ✅ Interface moderna e limpa
- ✅ Cores consistentes (azul como cor principal)
- ✅ Navegação intuitiva
- ✅ Feedback visual para todas as ações

### **Componentes:**
- ✅ Cards informativos
- ✅ Tabelas responsivas
- ✅ Formulários bem estruturados
- ✅ Modais e notificações
- ✅ Loading states

---

## 🏦 **Gateways de Pagamento**

### **Configurados e Prontos:**
- ✅ **Stripe** - Cartão de crédito/débito
- ✅ **Mercado Pago** - Pagamentos brasileiros
- ✅ **PIX** - Transferência instantânea

### **Configuração no .env:**
```env
STRIPE_KEY=sua_stripe_key
STRIPE_SECRET=sua_stripe_secret
MERCADOPAGO_PUBLIC_KEY=sua_mp_key
MERCADOPAGO_ACCESS_TOKEN=sua_mp_token
PIX_CHAVE=sua_chave_pix
```

---

## 📊 **Relatórios e Exportação**

### **Funcionalidades:**
- ✅ Relatórios em PDF (DomPDF)
- ✅ Importação/exportação Excel (Laravel Excel)
- ✅ Gráficos interativos (Chart.js)
- ✅ Backup automático
- ✅ Logs de atividade

---

## 🌐 **Multi-idioma**

### **Suporte:**
- ✅ Português (pt-BR) - Padrão
- ✅ Inglês (en) - Configurado
- ✅ Sistema de traduções
- ✅ Interface adaptável

---

## 🔧 **Manutenção e Suporte**

### **Comandos Úteis:**
```bash
# Limpar cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Backup do banco
php artisan backup:run

# Verificar status
php artisan about
```

### **Logs:**
- Logs de erro: `storage/logs/laravel.log`
- Logs de atividade: `storage/logs/activity.log`

---

## 📞 **Suporte Técnico**

**Desenvolvido por:** Vertex Solutions  
**CEO:** Reinan Rodrigues  
**Contato:** [informações de contato]

---

## 🎉 **Sistema 100% Funcional**

O sistema está completamente implementado e pronto para produção, incluindo:

✅ **Todas as funcionalidades solicitadas**  
✅ **Interface profissional e responsiva**  
✅ **Sistema de segurança robusto**  
✅ **Gateways de pagamento configurados**  
✅ **Multi-idioma implementado**  
✅ **Relatórios e exportação**  
✅ **Documentação completa**  

**🚀 Pronto para uso em produção!**
