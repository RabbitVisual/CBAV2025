<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Este arquivo agora serve como um ponto de entrada principal para
| carregar os arquivos de rota modularizados. Para uma melhor organização,
| as rotas foram divididas em arquivos específicos baseados em sua
| funcionalidade (público, autenticação, membro, admin).
|
*/

// Carrega as rotas de autenticação (login, logout)
require __DIR__.'/auth.php';

// Carrega as rotas públicas (home, eventos públicos, doações, etc.)
require __DIR__.'/public.php';

// Carrega as rotas da área de membros
require __DIR__.'/member.php';

// Carrega as rotas da área administrativa
require __DIR__.'/admin.php';