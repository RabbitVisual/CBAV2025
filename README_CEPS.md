# Sistema de CEPs - CBAV2025

## Visão Geral

Este sistema inclui uma funcionalidade completa para gerenciamento de CEPs brasileiros, baseada na base de dados disponível no repositório [database-CEPS](https://github.com/Maahzuka/database-CEPS).

## Estrutura Implementada

### 1. Migration
- **Arquivo**: `database/migrations/2025_09_06_220217_create_ceps_table.php`
- **Tabela**: `ceps`
- **Campos**: Todos os campos do arquivo Excel original
- **Índices**: Otimizados para consultas por CEP, UF e localidade

### 2. Model
- **Arquivo**: `app/Models/Cep.php`
- **Funcionalidades**:
  - Busca por CEP específico
  - Busca por UF
  - Busca por localidade
  - Formatação automática de CEP
  - Casting de tipos de dados

### 3. Seeder
- **Arquivo**: `database/seeders/CepSeeder.php`
- **Função**: Importa dados do arquivo Excel para o banco de dados

## Como Usar

### Passo 1: Baixar o Arquivo de Dados
1. Acesse: https://github.com/Maahzuka/database-CEPS
2. Baixe o arquivo `ceps.xlsx`
3. Coloque o arquivo na pasta `storage/app/` do seu projeto

### Passo 2: Executar a Migration
```bash
php artisan migrate
```

### Passo 3: Importar os Dados (Opcional)
```bash
php artisan db:seed --class=CepSeeder
```

## Exemplos de Uso no Código

### Buscar CEP específico
```php
use App\Models\Cep;

// Buscar por CEP
$cep = Cep::buscarPorCep('01310-100');

// Buscar CEPs de uma UF
$cepsRJ = Cep::buscarPorUf('RJ');

// Buscar por localidade
$cepsSaoPaulo = Cep::buscarPorLocalidade('São Paulo');
```

### Acessar dados formatados
```php
$cep = Cep::first();
echo $cep->cep_formatado; // Retorna CEP no formato 00000-000
echo $cep->localidade;   // Nome da cidade
echo $cep->uf;          // Estado
```

## Estrutura da Tabela

| Campo | Tipo | Descrição |
|-------|------|----------|
| id | bigint | Chave primária |
| uf | varchar(2) | Unidade Federativa |
| regiao | varchar(20) | Região do Brasil |
| localidade | varchar(100) | Nome da cidade |
| localidade_sem_acentos | varchar(100) | Nome da cidade sem acentos |
| faixa_de_cep | varchar(50) | Faixa de CEP |
| cep_inicial | varchar(8) | CEP inicial da faixa |
| cep_final | varchar(8) | CEP final da faixa |
| situacao | varchar(20) | Situação do CEP |
| tipo_de_faixa | varchar(50) | Tipo da faixa de CEP |
| latitude | decimal(10,8) | Coordenada de latitude |
| longitude | decimal(11,8) | Coordenada de longitude |
| cod_geografico_subdivisao | varchar(20) | Código geográfico da subdivisão |
| cod_geografico_distrito | varchar(20) | Código geográfico do distrito |
| cod_ibge | varchar(10) | Código IBGE |
| microrregiao | varchar(100) | Microrregião |
| mesorregiao | varchar(100) | Mesorregião |
| categoria | varchar(50) | Categoria |
| altitude | integer | Altitude em metros |
| localizacao | text | Informações de localização |
| localizacao_sem_acentos | text | Localização sem acentos |
| created_at | timestamp | Data de criação |
| updated_at | timestamp | Data de atualização |

## Índices para Performance

- Índice composto: `(cep_inicial, cep_final)`
- Índice: `uf`
- Índice: `localidade`
- Índice: `cod_ibge`

## Observações Importantes

1. **Tamanho dos Dados**: O arquivo Excel contém milhares de registros. A importação pode demorar alguns minutos.

2. **Memória**: Certifique-se de que o PHP tem memória suficiente para processar o arquivo Excel.

3. **Laravel Excel**: O seeder usa o pacote `maatwebsite/excel`. Se não estiver instalado, execute:
   ```bash
   composer require maatwebsite/excel
   ```

4. **Backup**: Sempre faça backup do banco antes de executar o seeder, pois ele limpa a tabela antes da importação.

## Integração com o Sistema de Membros

Esta funcionalidade pode ser integrada ao sistema de membros para:
- Validação automática de CEPs
- Preenchimento automático de cidade/estado
- Relatórios geográficos de membros
- Busca de membros por região

## Suporte

Para dúvidas ou problemas, consulte:
- Documentação do Laravel: https://laravel.com/docs
- Repositório de dados: https://github.com/Maahzuka/database-CEPS
- Laravel Excel: https://docs.laravel-excel.com/