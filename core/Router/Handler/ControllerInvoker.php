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

class ControllerInvoker {
   public function invoke(callable|array $callback, Request $request, Response $response): void {
      try {
         if (is_callable($callback)) {
            call_user_func($callback, $request, $response);
         } else {
            [$controller, $method] = $callback;
            if (!class_exists($controller)) {
               throw new \Exception("Controller {$controller} not found");
            }
            if (!method_exists($controller, $method)) {
               throw new \Exception("Method {$method} not found in controller {$controller}");
            }
            $currentController = new $controller(); # ToDO: revisar si se puede crear en el AP
            $response->classNamespace(get_class($currentController)); # ToDO: revisar si se puede crear en el AP
            call_user_func_array([$currentController, $method], [$request, $response]);
         }
      } catch (\Exception $e) {
         $response->setStatus(500)->json(['error_invoker' => $e->getMessage()]);
      }
   }
}