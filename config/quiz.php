<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações do Quiz Bíblico
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar as opções do sistema de quiz bíblico
    |
    */

    // Tempo limite por pergunta (em segundos)
    'tempo_limite' => env('QUIZ_TEMPO_LIMITE', 30),

    // Número de perguntas por sessão
    'perguntas_por_sessao' => env('QUIZ_PERGUNTAS_POR_SESSAO', 10),

    // Pontuação por nível
    'pontuacao' => [
        'facil' => env('QUIZ_PONTUACAO_FACIL', 10),
        'medio' => env('QUIZ_PONTUACAO_MEDIO', 20),
        'dificil' => env('QUIZ_PONTUACAO_DIFICIL', 30),
    ],

    // Notificar novos recordes
    'notificar_recordes' => env('QUIZ_NOTIFICAR_RECORDES', true),

    // Categorias disponíveis
    'categorias' => [
        'geral' => 'Geral',
        'antigo_testamento' => 'Antigo Testamento',
        'novo_testamento' => 'Novo Testamento',
        'personagens' => 'Personagens Bíblicos',
        'milagres' => 'Milagres',
        'parabolas' => 'Parábolas',
        'profetas' => 'Profetas',
        'apostolos' => 'Apóstolos',
    ],

    // Níveis disponíveis
    'niveis' => [
        'facil' => 'Fácil',
        'medio' => 'Médio',
        'dificil' => 'Difícil',
    ],

    // Configurações de exportação
    'exportacao' => [
        'formato_padrao' => 'excel', // excel ou pdf
        'incluir_estatisticas' => true,
        'incluir_melhores_pontuacoes' => true,
    ],
]; 