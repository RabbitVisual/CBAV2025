<?php

/**
 * Este teste arquitetural garante que as convenções de nomenclatura
 * para controladores sejam seguidas.
 */
test('controllers')
    ->expect('App\Http\Controllers\Admin')
    ->toBeClasses()
    ->toHaveSuffix('Controller');