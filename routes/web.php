<?php




/** @var \Laravel\Lumen\Routing\Router $router */


use Illuminate\Http\Response;


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'usuario'], function () use ($router) {
    $router->get('/listar', 'UsuarioController@index');
    $router->get('/exibir/{id}', 'UsuarioController@show');
    $router->post('/novo', 'UsuarioController@store');
    $router->post('/login', 'UsuarioController@login');
    $router->put('/update/{id}', 'UsuarioController@update');
    $router->delete('/destroy/{id}', 'UsuarioController@destroy');
});

$router->group(['prefix' => 'tipodespesa'], function () use ($router) {
    $router->get('/listar', 'TipoDespesaController@index');
    $router->get('/exibir/{id}', 'TipoDespesaController@show');
    $router->post('/novo', 'TipoDespesaController@store');
    $router->put('/update/{id}', 'TipoDespesaController@update');
    $router->delete('/destroy/{id}', 'TipoDespesaController@destroy');
});

$router->group(['prefix' => 'receita'], function () use ($router) {
    $router->get('/listar/usuario/{usuario_id}/mes/{mes}/ano/{ano}', 'LancamentoController@listaReceitas');
    $router->get('/exibir/{id}', 'ReceitaController@show');
    $router->post('/novo', 'ReceitaController@store');
    $router->put('/update/{id}', 'ReceitaController@update');
    $router->delete('/destroy/{id}', 'ReceitaController@destroy');
});

$router->group(['prefix' => 'lancamento'], function () use ($router) {
    $router->get('/listar', 'LancamentoController@index');

    $router->get('/detalhes/usuario/{usuario_id}/mes/{mes}/ano/{ano}/tipo/{tipo}', 'LancamentoController@listar');

    $router->get('/exibir/{id}', 'LancamentoController@show');

    $router->get('/saldo/usuario/{usuario_id}/mes/{mes}/ano/{ano}', 'LancamentoController@saldo');
    $router->get('/totalDespesas/usuario/{usuario_id}/mes/{mes}/ano/{ano}', 'LancamentoController@totaldespesas');
    $router->get('/totalReceita/usuario/{usuario_id}/mes/{mes}/ano/{ano}', 'LancamentoController@totalreceita');

    $router->get('/importarReceitas/usuario/{usuario_id}', 'LancamentoController@importarReceitas');

    $router->post('/novo', 'LancamentoController@store');
    $router->put('/update/{id}', 'LancamentoController@update');
    $router->delete('/destroy/{id}', 'LancamentoController@destroy');
});



/*
CREATE TABLE tipodespesa (
    tipodespesa INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id int NOT NULL,
    descricao VARCHAR(50),
    ativo bool default true
    );


CREATE TABLE receita (
    receita INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id int NOT NULL,
    descricao VARCHAR(50),
    valor decimal(10,2),
    fixo bool,
    ativo bool default true,
    updated_at timestamp default now(),
    created_at timestamp default now()
    );





CREATE TABLE lancamento (
    lancamento INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id int NOT NULL,
    tipo_lancamento int,
    receita_id int,
    descricao VARCHAR(50),
    valor decimal(10,2),
    debito_credito char(1),
    mes varchar(2),
    ano varchar(4),
    updated_at timestamp default now(),
    created_at timestamp default now()
    );




    */
