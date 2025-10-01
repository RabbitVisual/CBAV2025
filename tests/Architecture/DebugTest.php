<?php

/**
 * Este teste arquitetural garante que funções de depuração não sejam
 * deixadas no código-fonte da aplicação.
 */
test('globals')
    ->expect(['dd', 'dump', 'var_dump'])
    ->not->toBeUsed()
    ->in('App')
    ->ignoring('bootstrap/cache');