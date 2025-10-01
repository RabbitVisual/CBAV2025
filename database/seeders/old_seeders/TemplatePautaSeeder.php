<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplatePauta;
use App\Models\TemplateItemPauta;
use App\Models\User;

class TemplatePautaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar o primeiro usuário (admin)
        $admin = User::first();
        
        if (!$admin) {
            $this->command->error('Nenhum usuário encontrado. Execute o seeder de usuários primeiro.');
            return;
        }

        // Template 1: Reunião Ordinária Mensal
        $template1 = TemplatePauta::create([
            'nome' => 'Reunião Ordinária Mensal',
            'descricao' => 'Template padrão para reuniões ordinárias mensais do conselho',
            'categoria' => 'reuniao_ordinaria',
            'status' => 'ativo',
            'criado_por' => $admin->id
        ]);

        $itens1 = [
            [
                'titulo' => 'Abertura e Oração',
                'descricao' => 'Abertura da reunião com oração inicial',
                'tipo' => 'informativo',
                'prioridade' => 'alta',
                'ordem' => 1,
                'tempo_estimado' => 10,
                'observacoes' => 'Orar pela reunião e pelos presentes'
            ],
            [
                'titulo' => 'Leitura da Ata Anterior',
                'descricao' => 'Leitura e aprovação da ata da reunião anterior',
                'tipo' => 'deliberativo',
                'prioridade' => 'alta',
                'ordem' => 2,
                'tempo_estimado' => 15,
                'observacoes' => 'Verificar se há correções necessárias'
            ],
            [
                'titulo' => 'Relatório Financeiro',
                'descricao' => 'Apresentação do relatório financeiro mensal',
                'tipo' => 'informativo',
                'prioridade' => 'alta',
                'ordem' => 3,
                'tempo_estimado' => 20,
                'responsavel_id' => $admin->id,
                'observacoes' => 'Incluir receitas, despesas e saldo'
            ],
            [
                'titulo' => 'Relatório de Ministérios',
                'descricao' => 'Relatórios dos diversos ministérios da igreja',
                'tipo' => 'informativo',
                'prioridade' => 'media',
                'ordem' => 4,
                'tempo_estimado' => 30,
                'observacoes' => 'Cada ministério apresenta suas atividades'
            ],
            [
                'titulo' => 'Assuntos Gerais',
                'descricao' => 'Discussão de assuntos gerais da igreja',
                'tipo' => 'discussao',
                'prioridade' => 'media',
                'ordem' => 5,
                'tempo_estimado' => 25,
                'observacoes' => 'Incluir eventos, campanhas e necessidades'
            ],
            [
                'titulo' => 'Votação de Propostas',
                'descricao' => 'Votação de propostas e decisões importantes',
                'tipo' => 'votacao',
                'prioridade' => 'alta',
                'ordem' => 6,
                'tempo_estimado' => 20,
                'observacoes' => 'Registrar votos e resultados'
            ],
            [
                'titulo' => 'Próximos Eventos',
                'descricao' => 'Planejamento e organização de próximos eventos',
                'tipo' => 'discussao',
                'prioridade' => 'media',
                'ordem' => 7,
                'tempo_estimado' => 15,
                'observacoes' => 'Definir responsabilidades e datas'
            ],
            [
                'titulo' => 'Encerramento',
                'descricao' => 'Oração final e encerramento da reunião',
                'tipo' => 'informativo',
                'prioridade' => 'baixa',
                'ordem' => 8,
                'tempo_estimado' => 5,
                'observacoes' => 'Orar pela igreja e pelos próximos passos'
            ]
        ];

        foreach ($itens1 as $item) {
            TemplateItemPauta::create([
                'template_id' => $template1->id,
                'titulo' => $item['titulo'],
                'descricao' => $item['descricao'],
                'tipo' => $item['tipo'],
                'prioridade' => $item['prioridade'],
                'ordem' => $item['ordem'],
                'tempo_estimado' => $item['tempo_estimado'],
                'responsavel_id' => $item['responsavel_id'] ?? null,
                'observacoes' => $item['observacoes']
            ]);
        }

        // Template 2: Reunião Extraordinária
        $template2 = TemplatePauta::create([
            'nome' => 'Reunião Extraordinária',
            'descricao' => 'Template para reuniões extraordinárias do conselho',
            'categoria' => 'reuniao_extraordinaria',
            'status' => 'ativo',
            'criado_por' => $admin->id
        ]);

        $itens2 = [
            [
                'titulo' => 'Abertura e Oração',
                'descricao' => 'Abertura da reunião extraordinária',
                'tipo' => 'informativo',
                'prioridade' => 'alta',
                'ordem' => 1,
                'tempo_estimado' => 10,
                'observacoes' => 'Orar pela sabedoria para as decisões'
            ],
            [
                'titulo' => 'Apresentação do Assunto Urgente',
                'descricao' => 'Apresentação detalhada do assunto que motivou a reunião extraordinária',
                'tipo' => 'informativo',
                'prioridade' => 'urgente',
                'ordem' => 2,
                'tempo_estimado' => 30,
                'observacoes' => 'Incluir contexto, impactos e opções'
            ],
            [
                'titulo' => 'Discussão e Análise',
                'descricao' => 'Discussão detalhada do assunto com análise de prós e contras',
                'tipo' => 'discussao',
                'prioridade' => 'urgente',
                'ordem' => 3,
                'tempo_estimado' => 45,
                'observacoes' => 'Considerar todos os aspectos e implicações'
            ],
            [
                'titulo' => 'Votação da Decisão',
                'descricao' => 'Votação da decisão sobre o assunto urgente',
                'tipo' => 'votacao',
                'prioridade' => 'urgente',
                'ordem' => 4,
                'tempo_estimado' => 20,
                'observacoes' => 'Registrar votos e justificativas'
            ]
        ];

        foreach ($itens2 as $item) {
            TemplateItemPauta::create([
                'template_id' => $template2->id,
                'titulo' => $item['titulo'],
                'descricao' => $item['descricao'],
                'tipo' => $item['tipo'],
                'prioridade' => $item['prioridade'],
                'ordem' => $item['ordem'],
                'tempo_estimado' => $item['tempo_estimado'],
                'responsavel_id' => $item['responsavel_id'] ?? null,
                'observacoes' => $item['observacoes']
            ]);
        }

        // Template 3: Sessão de Votação
        $template3 = TemplatePauta::create([
            'nome' => 'Sessão de Votação',
            'descricao' => 'Template específico para sessões de votação',
            'categoria' => 'votacao',
            'status' => 'ativo',
            'criado_por' => $admin->id
        ]);

        $itens3 = [
            [
                'titulo' => 'Abertura da Sessão',
                'descricao' => 'Abertura da sessão de votação',
                'tipo' => 'informativo',
                'prioridade' => 'alta',
                'ordem' => 1,
                'tempo_estimado' => 10,
                'observacoes' => 'Verificar quórum e explicar regras'
            ],
            [
                'titulo' => 'Apresentação das Propostas',
                'descricao' => 'Apresentação detalhada das propostas a serem votadas',
                'tipo' => 'informativo',
                'prioridade' => 'alta',
                'ordem' => 2,
                'tempo_estimado' => 30,
                'observacoes' => 'Cada proposta deve ser apresentada claramente'
            ],
            [
                'titulo' => 'Perguntas e Esclarecimentos',
                'descricao' => 'Momento para perguntas e esclarecimentos sobre as propostas',
                'tipo' => 'discussao',
                'prioridade' => 'alta',
                'ordem' => 3,
                'tempo_estimado' => 20,
                'observacoes' => 'Esclarecer todas as dúvidas antes da votação'
            ],
            [
                'titulo' => 'Votação das Propostas',
                'descricao' => 'Votação das propostas apresentadas',
                'tipo' => 'votacao',
                'prioridade' => 'urgente',
                'ordem' => 4,
                'tempo_estimado' => 30,
                'observacoes' => 'Registrar cada voto individualmente'
            ],
            [
                'titulo' => 'Apuração e Resultados',
                'descricao' => 'Apuração dos votos e apresentação dos resultados',
                'tipo' => 'informativo',
                'prioridade' => 'alta',
                'ordem' => 5,
                'tempo_estimado' => 15,
                'observacoes' => 'Apresentar resultados e próximos passos'
            ]
        ];

        foreach ($itens3 as $item) {
            TemplateItemPauta::create([
                'template_id' => $template3->id,
                'titulo' => $item['titulo'],
                'descricao' => $item['descricao'],
                'tipo' => $item['tipo'],
                'prioridade' => $item['prioridade'],
                'ordem' => $item['ordem'],
                'tempo_estimado' => $item['tempo_estimado'],
                'responsavel_id' => $item['responsavel_id'] ?? null,
                'observacoes' => $item['observacoes']
            ]);
        }

        // Template 4: Reunião de Planejamento Anual
        $template4 = TemplatePauta::create([
            'nome' => 'Reunião de Planejamento Anual',
            'descricao' => 'Template para reunião anual de planejamento da igreja',
            'categoria' => 'evento',
            'status' => 'ativo',
            'criado_por' => $admin->id
        ]);

        $itens4 = [
            [
                'titulo' => 'Abertura e Oração',
                'descricao' => 'Abertura da reunião de planejamento anual',
                'tipo' => 'informativo',
                'prioridade' => 'alta',
                'ordem' => 1,
                'tempo_estimado' => 15,
                'observacoes' => 'Orar pela direção de Deus no planejamento'
            ],
            [
                'titulo' => 'Avaliação do Ano Anterior',
                'descricao' => 'Avaliação dos resultados e metas do ano anterior',
                'tipo' => 'informativo',
                'prioridade' => 'alta',
                'ordem' => 2,
                'tempo_estimado' => 45,
                'responsavel_id' => $admin->id,
                'observacoes' => 'Incluir sucessos, desafios e aprendizados'
            ],
            [
                'titulo' => 'Análise do Contexto Atual',
                'descricao' => 'Análise do contexto atual da igreja e comunidade',
                'tipo' => 'discussao',
                'prioridade' => 'alta',
                'ordem' => 3,
                'tempo_estimado' => 30,
                'observacoes' => 'Considerar mudanças, oportunidades e ameaças'
            ],
            [
                'titulo' => 'Definição de Metas e Objetivos',
                'descricao' => 'Definição das metas e objetivos para o próximo ano',
                'tipo' => 'deliberativo',
                'prioridade' => 'urgente',
                'ordem' => 4,
                'tempo_estimado' => 60,
                'observacoes' => 'Estabelecer metas SMART e responsabilidades'
            ],
            [
                'titulo' => 'Planejamento de Recursos',
                'descricao' => 'Planejamento dos recursos necessários para atingir as metas',
                'tipo' => 'discussao',
                'prioridade' => 'alta',
                'ordem' => 5,
                'tempo_estimado' => 40,
                'observacoes' => 'Incluir recursos humanos, financeiros e materiais'
            ],
            [
                'titulo' => 'Cronograma de Execução',
                'descricao' => 'Definição do cronograma de execução das ações',
                'tipo' => 'deliberativo',
                'prioridade' => 'alta',
                'ordem' => 6,
                'tempo_estimado' => 30,
                'observacoes' => 'Estabelecer prazos e marcos importantes'
            ],
            [
                'titulo' => 'Votação do Plano Anual',
                'descricao' => 'Votação para aprovação do plano anual',
                'tipo' => 'votacao',
                'prioridade' => 'urgente',
                'ordem' => 7,
                'tempo_estimado' => 20,
                'observacoes' => 'Aprovar o plano anual por unanimidade'
            ],
            [
                'titulo' => 'Encerramento e Compromisso',
                'descricao' => 'Encerramento com compromisso de execução',
                'tipo' => 'informativo',
                'prioridade' => 'media',
                'ordem' => 8,
                'tempo_estimado' => 15,
                'observacoes' => 'Orar pelo sucesso do plano e compromisso de todos'
            ]
        ];

        foreach ($itens4 as $item) {
            TemplateItemPauta::create([
                'template_id' => $template4->id,
                'titulo' => $item['titulo'],
                'descricao' => $item['descricao'],
                'tipo' => $item['tipo'],
                'prioridade' => $item['prioridade'],
                'ordem' => $item['ordem'],
                'tempo_estimado' => $item['tempo_estimado'],
                'responsavel_id' => $item['responsavel_id'] ?? null,
                'observacoes' => $item['observacoes']
            ]);
        }

        // Template 5: Reunião de Emergência
        $template5 = TemplatePauta::create([
            'nome' => 'Reunião de Emergência',
            'descricao' => 'Template para reuniões de emergência do conselho',
            'categoria' => 'reuniao_extraordinaria',
            'status' => 'ativo',
            'criado_por' => $admin->id
        ]);

        $itens5 = [
            [
                'titulo' => 'Abertura Urgente',
                'descricao' => 'Abertura da reunião de emergência',
                'tipo' => 'informativo',
                'prioridade' => 'urgente',
                'ordem' => 1,
                'tempo_estimado' => 5,
                'observacoes' => 'Explicar a urgência da situação'
            ],
            [
                'titulo' => 'Apresentação da Emergência',
                'descricao' => 'Apresentação detalhada da situação de emergência',
                'tipo' => 'informativo',
                'prioridade' => 'urgente',
                'ordem' => 2,
                'tempo_estimado' => 20,
                'observacoes' => 'Incluir fatos, impactos e urgência'
            ],
            [
                'titulo' => 'Análise Rápida',
                'descricao' => 'Análise rápida da situação e opções disponíveis',
                'tipo' => 'discussao',
                'prioridade' => 'urgente',
                'ordem' => 3,
                'tempo_estimado' => 25,
                'observacoes' => 'Considerar todas as opções rapidamente'
            ],
            [
                'titulo' => 'Decisão de Emergência',
                'descricao' => 'Tomada de decisão sobre a situação de emergência',
                'tipo' => 'votacao',
                'prioridade' => 'urgente',
                'ordem' => 4,
                'tempo_estimado' => 15,
                'observacoes' => 'Decisão rápida e eficaz'
            ],
            [
                'titulo' => 'Ações Imediatas',
                'descricao' => 'Definição das ações imediatas a serem tomadas',
                'tipo' => 'deliberativo',
                'prioridade' => 'urgente',
                'ordem' => 5,
                'tempo_estimado' => 20,
                'observacoes' => 'Definir responsabilidades e prazos imediatos'
            ]
        ];

        foreach ($itens5 as $item) {
            TemplateItemPauta::create([
                'template_id' => $template5->id,
                'titulo' => $item['titulo'],
                'descricao' => $item['descricao'],
                'tipo' => $item['tipo'],
                'prioridade' => $item['prioridade'],
                'ordem' => $item['ordem'],
                'tempo_estimado' => $item['tempo_estimado'],
                'responsavel_id' => $item['responsavel_id'] ?? null,
                'observacoes' => $item['observacoes']
            ]);
        }

        $this->command->info('Templates de pauta criados com sucesso!');
        $this->command->info('Total de templates criados: 5');
        $this->command->info('Total de itens criados: ' . (count($itens1) + count($itens2) + count($itens3) + count($itens4) + count($itens5)));
    }
} 