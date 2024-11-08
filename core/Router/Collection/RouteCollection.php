<?php
/**
 * @package     core/Router
 * @subpackage  Collection
 * @file        RouteCollection
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-07 12:13:17
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router\Collection;

class RouteCollection {
   private array $routes = [];

   public function addRouter(string $method, string $path, callable|array $callback, array $middlewares = []): void {
      $this->routes[$method][$this->normalizePath($path)] = [
         'callback' => $callback,
         'middlewares' => $middlewares
      ];
   }

   private function normalizePath(string $path): string {
      return $path === '/' ? $path : rtrim($path, '/');
   }

   public function group(callable $callback, array $middlewares = []): void {
      $callback(new class ($this, $middlewares) {
         public function __construct(private RouteCollection $routeCollection, private array $middlewares) {
         }

         public function get(string $path, callable|array $callback, array $middlewares = []): void {
            $this->routeCollection->addRouter('GET', $path, $callback, array_merge($this->middlewares, $middlewares));
         }

         public function post(string $path, callable|array $callback, array $middlewares = []): void {
            $this->routeCollection->addRouter('POST', $path, $callback, array_merge($this->middlewares, $middlewares));
         }

         public function put(string $path, callable|array $callback, array $middlewares = []): void {
            $this->routeCollection->addRouter('PUT', $path, $callback, array_merge($this->middlewares, $middlewares));
         }

         public function delete(string $path, callable|array $callback, array $middlewares = []): void {
            $this->routeCollection->addRouter('DELETE', $path, $callback, array_merge($this->middlewares, $middlewares));
         }
      });
   }

   public function getRoutes(): array {
      return $this->routes;
   }
}