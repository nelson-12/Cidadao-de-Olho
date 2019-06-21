<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('deputados')->group(function() {
    
    Route::get('/midia', 'DeputadosController@Midia');
    Route::get('/{id}', 'DeputadosController@index');
    Route::get('/gastosDeputados/{id}/{mes}', 'DeputadosController@gastos');

});
