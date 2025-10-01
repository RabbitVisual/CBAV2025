# 🏛️ CBAV CRM Ministerial - Sistema Completo de Gestão Eclesiástica

<div align="center">

<img src="public/img/logocbav.png" alt="CBAV Logo" width="200" height="200">

![CBAV Logo](https://img.shields.io/badge/CBAV-CRM%20Ministerial-blue?style=for-the-badge&logo=church)
![Laravel](https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php)
![License](https://img.shields.io/badge/License-Private-red?style=for-the-badge)

**Sistema completo de gestão ministerial para a Congregação Batista Avenida**

---

<img src="public/img/logo.png" alt="Vertex Solutions Logo" width="150" height="80">

**Desenvolvido pela Vertex Solutions LTDA**

<img src="public/img/reinanrodrigues.jpg" alt="Reinan Rodrigues - CEO" width="100" height="100" style="border-radius: 50%;">

**CEO: Reinan Rodrigues**

[![WhatsApp](https://img.shields.io/badge/WhatsApp-+5575992034656-green?style=for-the-badge&logo=whatsapp)](https://wa.me/5575992034656)
[![Email](https://img.shields.io/badge/Email-r.rodriguesjs@gmail.com-blue?style=for-the-badge&logo=gmail)](mailto:r.rodriguesjs@gmail.com)

</div>

---

## 📋 **Índice**

-   [🎯 Visão Geral](#-visão-geral)
-   [🚀 Tecnologias](#-tecnologias)
-   [🏗️ Arquitetura do Sistema](#️-arquitetura-do-sistema)
-   [📱 Módulos Principais](#-módulos-principais)
-   [🔐 Sistema de Segurança](#-sistema-de-segurança)
-   [💰 Sistema Financeiro](#-sistema-financeiro)
-   [📚 EBD Digital](#-ebd-digital)
-   [🏛️ Conselho Ministerial](#️-conselho-ministerial)
-   [📖 Devocionais e Bíblia Digital](#-devocionais-e-bíblia-digital)
-   [📅 Sistema de Eventos](#-sistema-de-eventos)
-   [💬 Chat e Comunicação](#-chat-e-comunicação)
-   [🛠️ Instalação](#️-instalação)
-   [⚙️ Configuração](#️-configuração)
-   [📊 Relatórios e Exportação](#-relatórios-e-exportação)
-   [🔧 Manutenção](#-manutenção)
-   [📞 Suporte](#-suporte)
-   [📄 Licença](#-licença)

---

## 🎯 **Visão Geral**

<div align="center">

<img src="public/img/logocbav.png" alt="CBAV Logo" width="100" height="100">

</div>

O **CBAV CRM Ministerial** é um sistema completo e integrado desenvolvido especificamente para gestão de igrejas e congregações. O sistema oferece uma solução moderna e robusta para administração ministerial, incluindo gestão de membros, controle financeiro, EBD digital, conselho ministerial, devocionais, eventos e muito mais.

### **Características Principais:**

-   ✅ **Sistema 100% Funcional** - Pronto para produção
-   ✅ **Interface Moderna** - Design responsivo e intuitivo
-   ✅ **Multi-módulo** - Funcionalidades integradas
-   ✅ **Segurança Avançada** - Controle de acesso granular
-   ✅ **Escalável** - Suporta crescimento da congregação
-   ✅ **Multi-idioma** - Português e Inglês
-   ✅ **API REST** - Integração com sistemas externos

---

## 🚀 **Tecnologias**

### **Backend:**

-   **Laravel 12** - Framework PHP moderno
-   **PHP 8.2+** - Linguagem de programação
-   **MySQL 5.7+** - Banco de dados relacional
-   **Laravel Sanctum** - Autenticação API
-   **Spatie Laravel Permission** - Controle de permissões

### **Frontend:**

-   **Blade Templates** - Sistema de templates Laravel
-   **Tailwind CSS** - Framework CSS utilitário
-   **Alpine.js** - JavaScript reativo
-   **Livewire** - Componentes dinâmicos
-   **Chart.js** - Gráficos interativos

### **Integrações:**

-   **Stripe** - Pagamentos internacionais
-   **Mercado Pago** - Pagamentos brasileiros
-   **PIX** - Transferências instantâneas
-   **Bible API** - Integração bíblica
-   **Laravel Excel** - Importação/Exportação
-   **DomPDF** - Geração de PDFs

---

## 🏗️ **Arquitetura do Sistema**

```
CBAV CRM Ministerial/
├── 📁 app/
│   ├── 📁 Http/Controllers/     # Controladores da aplicação
│   ├── 📁 Models/              # Modelos Eloquent
│   ├── 📁 Services/            # Serviços de negócio
│   ├── 📁 Events/              # Eventos do sistema
│   ├── 📁 Listeners/           # Ouvintes de eventos
│   └── 📁 Helpers/             # Classes auxiliares
├── 📁 resources/
│   ├── 📁 views/               # Templates Blade
│   ├── 📁 css/                 # Estilos CSS
│   └── 📁 js/                  # JavaScript
├── 📁 database/
│   ├── 📁 migrations/          # Migrações do banco
│   └── 📁 seeders/             # Seeders de dados
├── 📁 public/                  # Arquivos públicos
└── 📁 docs/                    # Documentação completa
```

---

## 📱 **Módulos Principais**

### 👥 **1. Gestão de Membros**

-   **Cadastro Completo** - Dados pessoais e ministeriais
-   **Upload de Fotos** - Gestão de imagens
-   **Vínculos Ministeriais** - Associação a ministérios e cargos
-   **Histórico de Participação** - Acompanhamento de atividades
-   **Dados de Contato** - Informações de comunicação
-   **Status Ministerial** - Controle de membros ativos/inativos

### 🏛️ **2. Gestão Ministerial**

-   **Ministérios** - Criação e gestão de ministérios
-   **Departamentos** - Estrutura hierárquica
-   **Cargos e Funções** - Definição de responsabilidades
-   **Estrutura Organizacional** - Organograma da igreja
-   **Cores e Ícones** - Personalização visual

### 💰 **3. Sistema Financeiro**

-   **Transações** - Dízimos, ofertas, campanhas e saídas
-   **Campanhas de Arrecadação** - Gestão de campanhas
-   **Progresso Visual** - Acompanhamento de metas
-   **Upload de Comprovantes** - Documentação financeira
-   **Relatórios Financeiros** - Análise de receitas e despesas
-   **Gateways de Pagamento** - Stripe, Mercado Pago, PIX

### 📚 **4. EBD Digital**

-   **Gestão de Alunos** - Cadastro e matrículas
-   **Gestão de Professores** - Controle de educadores
-   **Turmas e Grupos** - Organização de classes
-   **Lições e Aulas** - Conteúdo educacional
-   **Sistema de Avaliações** - Quiz e provas
-   **Certificados** - Emissão automática
-   **Relatórios de Frequência** - Controle de presença

### 🏛️ **5. Conselho Ministerial**

-   **Reuniões** - Agendamento e gestão
-   **Pautas** - Itens de discussão
-   **Votações** - Sistema de votação digital
-   **Ata de Reuniões** - Documentação oficial
-   **Alertas de Quórum** - Notificações automáticas
-   **Histórico de Decisões** - Arquivo de deliberações

### 📖 **6. Devocionais e Bíblia Digital**

-   **Devocionais Diários** - Conteúdo espiritual
-   **Bíblia Digital** - Leitura online
-   **Referências Bíblicas** - Links automáticos
-   **Estudos Temáticos** - Conteúdo organizado
-   **Sistema de Busca** - Pesquisa por versículos
-   **Múltiplas Versões** - Diferentes traduções

### 📅 **7. Sistema de Eventos**

-   **Criação de Eventos** - Gestão completa
-   **Inscrições** - Sistema de cadastro
-   **Pagamentos** - Integração financeira
-   **Certificados** - Emissão automática
-   **Relatórios** - Análise de participação
-   **Calendário** - Visualização temporal

### 💬 **8. Chat e Comunicação**

-   **Chat Interno** - Comunicação entre membros
-   **Notificações** - Sistema de alertas
-   **Pedidos de Oração** - Solicitações espirituais
-   **Intercessores** - Rede de oração
-   **Templates de Mensagem** - Comunicação padronizada

---

## 🔐 **Sistema de Segurança**

### **Níveis de Acesso:**

#### 👑 **Super Administrador**

-   Acesso total ao sistema
-   Gestão de usuários e permissões
-   Configurações do sistema
-   Backup e manutenção
-   Logs de atividade

#### 🏛️ **Pastor**

-   Gestão de membros
-   Gestão de ministérios
-   Visualização de relatórios
-   Gestão de campanhas
-   Acesso ao conselho

#### 💰 **Tesoureiro**

-   Gestão de transações
-   Relatórios financeiros
-   Gestão de campanhas
-   Upload de comprovantes
-   Controle de pagamentos

#### 👥 **Líder**

-   Visualização de membros
-   Gestão de cargos
-   Relatórios básicos
-   Acesso limitado

### **Recursos de Segurança:**

-   **Autenticação Segura** - Laravel Sanctum
-   **Permissões Granulares** - Controle detalhado
-   **Middleware de Proteção** - Validação de acesso
-   **Logs de Atividade** - Auditoria completa
-   **Sessões Seguras** - Controle de login

---

## 💰 **Sistema Financeiro**

### **Funcionalidades:**

-   **Transações Completas** - Entradas e saídas
-   **Categorização** - Dízimos, ofertas, campanhas
-   **Campanhas de Arrecadação** - Metas e progresso
-   **Comprovantes** - Upload de documentos
-   **Relatórios Detalhados** - Análise financeira
-   **Exportação** - Excel e PDF

### **Gateways de Pagamento:**

-   **Stripe** - Cartões internacionais
-   **Mercado Pago** - Pagamentos brasileiros
-   **PIX** - Transferências instantâneas
-   **Configuração Flexível** - Múltiplas opções

---

## 📚 **EBD Digital**

### **Funcionalidades:**

-   **Gestão de Alunos** - Cadastro completo
-   **Sistema de Turmas** - Organização por idade
-   **Lições Interativas** - Conteúdo digital
-   **Avaliações Online** - Quiz e provas
-   **Certificados Automáticos** - Emissão digital
-   **Relatórios de Frequência** - Controle de presença
-   **Sistema de Notas** - Avaliação de desempenho

---

## 🏛️ **Conselho Ministerial**

### **Funcionalidades:**

-   **Agendamento de Reuniões** - Calendário integrado
-   **Gestão de Pautas** - Itens de discussão
-   **Sistema de Votação** - Decisões digitais
-   **Ata de Reuniões** - Documentação oficial
-   **Alertas de Quórum** - Notificações automáticas
-   **Histórico de Decisões** - Arquivo completo

---

## 📖 **Devocionais e Bíblia Digital**

### **Funcionalidades:**

-   **Devocionais Diários** - Conteúdo espiritual
-   **Bíblia Digital** - Leitura online
-   **Múltiplas Versões** - Diferentes traduções
-   **Sistema de Busca** - Pesquisa por versículos
-   **Referências Automáticas** - Links contextuais
-   **Estudos Temáticos** - Conteúdo organizado

---

## 📅 **Sistema de Eventos**

### **Funcionalidades:**

-   **Criação de Eventos** - Gestão completa
-   **Sistema de Inscrições** - Cadastro online
-   **Pagamentos Integrados** - Gateway de pagamento
-   **Certificados Automáticos** - Emissão digital
-   **Relatórios de Participação** - Análise de dados
-   **Calendário Integrado** - Visualização temporal

---

## 💬 **Chat e Comunicação**

### **Funcionalidades:**

-   **Chat Interno** - Comunicação entre membros
-   **Sistema de Notificações** - Alertas automáticos
-   **Pedidos de Oração** - Solicitações espirituais
-   **Rede de Intercessores** - Conexão espiritual
-   **Templates de Mensagem** - Comunicação padronizada

---

## 🛠️ **Instalação**

### **Pré-requisitos:**

-   PHP 8.2 ou superior
-   MySQL 5.7 ou superior
-   Composer
-   Node.js 16+ (para compilação de assets)
-   Servidor web (Apache/Nginx)

### **Método 1: Instalador Web (Recomendado)**

1. **Faça upload dos arquivos** para seu servidor
2. **Acesse:** `https://seudominio.com/install.php`
3. **Siga o assistente** de instalação
4. **Configure** banco de dados e super admin
5. **Pronto!** Sistema instalado automaticamente

### **Método 2: Instalação Manual**

```bash
# 1. Clone o repositório
git clone https://github.com/vertex-solutions/cbav-crm.git
cd cbav-crm

# 2. Instale as dependências
composer install
npm install

# 3. Configure o ambiente
cp .env.example .env
php artisan key:generate

# 4. Configure o banco de dados no .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cbav
DB_USERNAME=root
DB_PASSWORD=sua_senha

# 5. Execute as migrações
php artisan migrate

# 6. Execute os seeders
php artisan db:seed

# 7. Compile os assets
npm run build

# 8. Inicie o servidor
php artisan serve
```

### **Método 3: Instalação via Comando**

```bash
# Instalação completa via comando
php artisan cbav:install

# Verificação do sistema
php artisan cbav:check

# Verificação com correções automáticas
php artisan cbav:check --fix
```

---

## ⚙️ **Configuração**

### **Configurações Básicas:**

```env
# Aplicação
APP_NAME="CBAV CRM Ministerial"
APP_ENV=production
APP_KEY=base64:your-app-key
APP_DEBUG=false
APP_URL=https://seudominio.com

# Banco de Dados
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cbav
DB_USERNAME=root
DB_PASSWORD=sua_senha

# Pagamentos
STRIPE_KEY=sua_stripe_key
STRIPE_SECRET=sua_stripe_secret
MERCADOPAGO_PUBLIC_KEY=sua_mp_key
MERCADOPAGO_ACCESS_TOKEN=sua_mp_token
PIX_CHAVE=sua_chave_pix

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_app
MAIL_ENCRYPTION=tls
```

### **Configurações Avançadas:**

```bash
# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Otimização
php artisan optimize
php artisan optimize:clear
```

---

## 📊 **Relatórios e Exportação**

### **Funcionalidades:**

-   **Relatórios em PDF** - DomPDF
-   **Exportação Excel** - Laravel Excel
-   **Gráficos Interativos** - Chart.js
-   **Backup Automático** - Sistema de backup
-   **Logs de Atividade** - Auditoria completa

### **Tipos de Relatórios:**

-   **Financeiro** - Receitas, despesas, campanhas
-   **Membros** - Cadastro, frequência, participação
-   **EBD** - Alunos, professores, turmas
-   **Eventos** - Participação, arrecadação
-   **Conselho** - Reuniões, decisões, votações

---

## 🔧 **Manutenção**

### **Comandos Úteis:**

```bash
# Instalação e Configuração
php artisan cbav:install
php artisan cbav:check
php artisan cbav:check --fix

# Backup do Sistema
php artisan backup:sistema
php artisan backup:run

# Limpeza de Cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Manutenção
php artisan about
php artisan route:list
php artisan permission:cache-reset

# Logs
tail -f storage/logs/laravel.log
```

### **Monitoramento:**

-   **Logs de Erro** - `storage/logs/laravel.log`
-   **Logs de Atividade** - `storage/logs/activity.log`
-   **Status do Sistema** - `php artisan about`
-   **Verificação de Integridade** - `php artisan cbav:check`

---

## 📞 **Suporte**

<div align="center">

<img src="public/img/logo.png" alt="Vertex Solutions Logo" width="200" height="100">

**Vertex Solutions LTDA**

<img src="public/img/reinanrodrigues.jpg" alt="Reinan Rodrigues - CEO" width="120" height="120" style="border-radius: 50%;">

**CEO: Reinan Rodrigues**

</div>

### **Informações de Contato:**
-   **WhatsApp:** [+55 75 99203-4656](https://wa.me/5575992034656)
-   **Email:** [r.rodriguesjs@gmail.com](mailto:r.rodriguesjs@gmail.com)
-   **Website:** [Vertex Solutions](https://vertexsolutions.com.br)

### **Horário de Atendimento:**

-   **Segunda a Sexta:** 8h às 18h
-   **Sábado:** 8h às 12h
-   **Domingo:** Fechado

### **Tipos de Suporte:**

-   **Suporte Técnico** - Resolução de problemas
-   **Treinamento** - Capacitação de usuários
-   **Customização** - Desenvolvimento de funcionalidades
-   **Manutenção** - Atualizações e melhorias

---

## 📄 **Licença**

### **Licença Privada - Vertex Solutions LTDA**

Este software é propriedade da **Vertex Solutions LTDA** e está protegido por direitos autorais. O uso deste sistema é restrito e requer autorização expressa da empresa.

#### **Termos de Uso:**

-   ✅ **Uso Autorizado** - Apenas para congregações licenciadas
-   ❌ **Distribuição Proibida** - Não pode ser redistribuído
-   ❌ **Modificação Restrita** - Alterações apenas com autorização
-   ❌ **Engenharia Reversa** - Proibida a descompilação
-   ❌ **Uso Comercial** - Proibido uso para fins comerciais

#### **Penalidades:**

-   Violação dos termos resultará em ação legal
-   Multa de até R$ 50.000,00 por violação
-   Indenização por danos morais e materiais
-   Suspensão imediata da licença

#### **Contato para Licenciamento:**

-   **Email:** [r.rodriguesjs@gmail.com](mailto:r.rodriguesjs@gmail.com)
-   **WhatsApp:** [+55 75 99203-4656](https://wa.me/5575992034656)

---

<div align="center">

<img src="public/img/logocbav.png" alt="CBAV Logo" width="150" height="150">

**Congregação Batista Avenida**

---

<img src="public/img/logo.png" alt="Vertex Solutions Logo" width="120" height="60">

**© 2025 Vertex Solutions LTDA - Todos os direitos reservados**

<img src="public/img/reinanrodrigues.jpg" alt="Reinan Rodrigues - CEO" width="80" height="80" style="border-radius: 50%;">

**CEO: Reinan Rodrigues**

_Sistema desenvolvido com ❤️ para a glória de Deus_

[![GitHub](https://img.shields.io/badge/GitHub-Private-red?style=for-the-badge&logo=github)](https://github.com/RabbitVisual/CBAV2025)
[![WhatsApp](https://img.shields.io/badge/WhatsApp-+5575992034656-green?style=for-the-badge&logo=whatsapp)](https://wa.me/5575992034656)

</div>
