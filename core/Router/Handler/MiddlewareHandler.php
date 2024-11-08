<?php
/**
 * @package     core/Router
 * @subpackage  Handler
 * @file        MiddlewareHandler
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-08 00:17:38
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router\Handler;

use ESE\Core\Router\Http\Request;
use ESE\Core\Router\Http\Response;

class MiddlewareHandler {
   public function handle(array $middlewares, Request $request, Response $response) {
      foreach ($middlewares as $middleware) {
         // evalua si el middleware es un {closure} o una clase
         if (is_callable($middleware)) {
            call_user_func($middleware, $request, $response);
            continue;
         }

         if (!class_exists($middleware)) {
            throw new \Exception("Middleware {$middleware} not found");
         }


         // $middleware = new $middleware;
         // $middleware->handle($request, $response);
      }
   }
}