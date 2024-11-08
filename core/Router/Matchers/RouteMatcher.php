<?php
/**
 * @package     core/Router
 * @subpackage  Matchers
 * @file        RouteMatcher
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-08 00:19:16
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router\Matchers;

use ESE\Core\Router\Collection\RouteCollection;

class RouteMatcher {
   public function match(string $method, string $uri, RouteCollection $collection): ?array {
      $routes = $collection->getRoutes();

      if (!isset($routes[$method])) {
         return null;
      }

      foreach ($routes[$method] as $path => $route) {

         $params = $this->matchRoute($path, $uri);
         if ($params !== null) {
            return array_merge($route, ['params' => $params]);
         }
      }

      return null;
   }

   private function matchRoute(string $path, string $uri): ?array {
      $pathRegex = preg_replace('/\{([a-zA-Z0-9_]+)\??\}/', '([a-zA-Z0-9_]+)?', $path);
      $pathRegex = "#^" . $pathRegex . "$#";
      if (preg_match($pathRegex, $uri, $matches)) {
         array_shift($matches); // Elimina el primer valor que es la ruta completa
         return $matches;
      }
      return null;
   }
}