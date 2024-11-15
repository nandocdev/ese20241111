<?php
/**
 * @package     srv/esecorp
 * @subpackage  public
 * @file        index
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-01 08:39:54
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

use ESE\Core\Router\Collection\RouteCollection;
use ESE\Core\Router\Matchers\RouteMatcher;
use ESE\Core\Router\Router;
use ESE\Core\Router\Handler\ControllerInvoker;
use ESE\Core\Router\Handler\MiddlewareHandler;
use ESE\Core\Router\Http\Request;
use ESE\Core\Router\Http\Response;

// habilita los errores php
// TODO: Deshabilitar en producción
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// carga autoload de composer
require_once __DIR__ . '/../vendor/autoload.php';


// Autocarga las dependencias de Composer
require_once __DIR__ . '/../core/Bootstrap/Kernel.php';

// Activa la carga de configuraciones, sesiones y contenedor de dependencias
try {
   // Inicializa el Kernel y carga las configuraciones esenciales
   $container = ESE\Core\Bootstrap\Kernel::run();

   // implementa el manejador de errores
   set_error_handler('ESE\Core\Handler\EseHandlerException::errorHandler');
   set_exception_handler('ESE\Core\Handler\EseHandlerException::exceptionHandler');

   // Aquí puedes inyectar el contenedor de dependencias o invocar controladores
   // Por ejemplo, podrías obtener el enrutador del contenedor y procesar la solicitud
   // $router = $container->get(\App\Router::class);
   // $router->dispatch();

   // Crear el router
   $router = new Router(
      new RouteCollection(),
      new RouteMatcher(),
      new ControllerInvoker($container),
      new MiddlewareHandler($container),
      $container
   );

   // Cargar las rutas
   // Definir algunas rutas
   $router->get('/home', function (Request $request, Response $response) {
      $response->json(['message' => 'Welcome to the home page']);
   });

   $router->get('/user/{name}/{surename}/{age}?', function (Request $request, Response $response) {
      $response->json([
         'message' => 'Welcome to the user page',
         'name' => $request->body['name'],
         'surename' => $request->body['surename'],
         'age' => $request->body['age']
      ]);
   });

   // Implementación de la lógica para el router
   $router->dispatch();

} catch (\Exception $e) {
   // Manejo de excepciones globales
   // En producción, es recomendable redirigir a una página de error personalizada
   error_log($e->getMessage());
   http_response_code(500);
   echo "Se ha producido un error en el arranque de la aplicación " . $e->getMessage();
}