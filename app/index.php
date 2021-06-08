<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once '../vendor/autoload.php';
require_once './clases/AccesoDatos.php';

require_once './clases/usuarioApi.php';
require_once './clases/ProductoApi.php';
require_once './clases/MesaApi.php';
require_once './clases/PedidoApi.php';


require_once './clases autenticacion/MWparaCORS.php';
require_once './clases autenticacion/MWParaAutenticar.php';


$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
$app = new \Slim\App(["settings" => $config]);


use Illuminate\Database\Capsule\Manager as Capsule;

//Eloquent
$container=$app->getContainer();

//mysql:host=remotemysql.com;dbname=0qs1bpp6xL;charset=utf8', '0qs1bpp6xL', 'Gl4Mlm6HbD',

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'remotemysql.com',
    'database'  => '0qs1bpp6xL',
    'username'  => '0qs1bpp6xL',
    'password'  => 'Gl4Mlm6HbD',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
///////////////////////





/*LLAMADA A METODOS DE INSTANCIA DE UNA CLASE*/

$app->post('/login', \usuarioApi::class . ':LoginUsuario');

$app->group('/usuario', function () {
 
  $this->get('/', \usuarioApi::class . ':traerTodos');
 
  $this->get('/{id}', \usuarioApi::class . ':traerUno');

  $this->post('/', \usuarioApi::class . ':CargarUno');

  $this->delete('/{id}', \usuarioApi::class . ':BorrarUno');

  $this->put('/', \usuarioApi::class . ':ModificarUno');

})->add(\MWParaAutenticar::class . ':VerificarUsuario')->add(\MWparaCORS::class . ':HabilitarCORS8080');

$app->group('/producto', function () {
 
  $this->get('/', \ProductoApi::class . ':traerTodos');
   
  $this->get('/{id}', \ProductoApi::class . ':traerUno');
  
  $this->post('/', \ProductoApi::class . ':CargarUno');
  
  $this->delete('/{id}', \ProductoApi::class . ':BorrarUno');
  
  $this->put('/', \ProductoApi::class . ':ModificarUno');
  
       
})->add(\MWParaAutenticar::class . ':VerificarUsuario')->add(\MWparaCORS::class . ':HabilitarCORS8080');

$app->group('/mesa', function () {
 
  $this->get('/', \MesaApi::class . ':traerTodos');
   
  $this->get('/{id}', \MesaApi::class . ':traerUno');
  
  $this->post('/', \MesaApi::class . ':CargarUno');
  
  $this->delete('/{id}', \MesaApi::class . ':BorrarUno');
  
  $this->put('/', \MesaApi::class . ':ModificarUno');
         
})->add(\MWParaAutenticar::class . ':VerificarUsuario')->add(\MWparaCORS::class . ':HabilitarCORS8080');

$app->group('/pedido', function () {
 
  $this->get('/', \PedidoApi::class . ':traerTodos');
   
  $this->get('/{id}', \PedidoApi::class . ':traerUno');
  
  $this->post('/', \PedidoApi::class . ':CargarUno');
  
  $this->delete('/{id}', \PedidoApi::class . ':BorrarUno');
  
  $this->put('/tomar', \PedidoApi::class . ':TomarPedidoPendiente');

  $this->put('/servir', \PedidoApi::class . ':ServirPedidoListo');
       
})->add(\MWParaAutenticar::class . ':VerificarUsuario')->add(\MWparaCORS::class . ':HabilitarCORS8080');


$app->group('/pendientes', function(){

  $this->get('/', \PedidoApi::class . ':TraerPedidoPendiente');

})->add(\MWParaAutenticar::class . ':VerificarUsuario')->add(\MWparaCORS::class . ':HabilitarCORS8080');

$app->group('/cliente', function(){

  $this->get('/{numero_pedido}', \PedidoApi::class . ':ConsultarTiempoEspera');

  $this->post('/pagar', \PedidoApi::class . ':PagarCuenta');

  $this->post('/puntuar', \PedidoApi::class . ':PuntuarAtencion');

});


$app->group('/csv/descargar', function(){

  $this->get('/usuarios', \auxUsuario::class . ':GenerarCSV');

  $this->get('/productos', \auxProducto::class . ':GenerarCSV');

  $this->get('/mesas', \auxMesa::class . ':GenerarCSV');

  $this->get('/pedidos', \auxPedido::class . ':GenerarCSV');

});

$app->group('/csv/carga', function(){

  $this->post('/usuarios', \auxUsuario::class . ':CargarDeCSV');

  $this->post('/productos', \auxProducto::class . ':CargarDeCSV');

  $this->post('/mesas', \auxMesa::class . ':CargarDeCSV');

  $this->post('/pedidos', \auxPedido::class . ':CargarDeCSV');

});

$app->group('/pdf', function(){

  $this->get('/usuarios', \auxUsuario::class . ':GenerarPDF');

  $this->get('/productos', \auxProducto::class . ':GenerarPDF');

  $this->get('/mesas', \auxMesa::class . ':GenerarPDF');

  $this->get('/pedidos', \auxPedido::class . ':GenerarPDF');

});


//------------------------------------------------------------//
//Listados:

$app->group('/administracion/empleado', function(){

  $this->get('/a', \auxUsuario::class . ':listadoA');

  $this->get('/b', \auxUsuario::class . ':ticetksGet');

  $this->get('/c', \auxMesa::class . ':listadoC');

  $this->get('/d', \auxPedido::class . ':listadoD');

});

$app->run();














?>