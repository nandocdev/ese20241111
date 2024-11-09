<?php
/**
 * @package     core/Router
 * @subpackage  Handler
 * @file        ControllerInvoker
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-08 00:06:45
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router\Handler;

use ESE\Core\Router\Http\Request;
use ESE\Core\Router\Http\Response;
use Psr\Container\ContainerInterface;
use DI\Container;

class ControllerInvoker {
   private ContainerInterface $container;

   public function __construct(ContainerInterface $container) {
      $this->container = $container;
   }

   public function invoke(callable|array $callback, Request $request, Response $response): void {
      try {
         if (is_callable($callback)) {
            call_user_func($callback, $request, $response);
         } else {
            [$controller, $method] = $callback;

            // Verificación de la existencia del controlador y método
            if (!class_exists($controller)) {
               throw new \Exception("Controller {$controller} not found");
            }
            if (!method_exists($controller, $method)) {
               throw new \Exception("Method {$method} not found in controller {$controller}");
            }

            // Creación del controlador con o sin contenedor de dependencias
            $currentController = $this->createControllerInstance($controller);

            // Registrar el namespace de la clase en la respuesta
            $response->classNamespace(get_class($currentController));

            // Invocar el método del controlador con Request y Response como parámetros
            call_user_func_array([$currentController, $method], [$request, $response]);
         }
      } catch (\TypeError $e) {
         $response->setStatus(400)->json(['error' => 'Invalid arguments: ' . $e->getMessage()]);
      } catch (\Exception $e) {
         $response->setStatus(500)->json(['error_invoker' => $e->getMessage()]);
      }
   }

   private function createControllerInstance(string $controller) {
      if ($this->container && $this->container->has($controller)) {
         return $this->container->get($controller);
      }
      return new $controller();
   }
}