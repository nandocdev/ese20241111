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

   // Método para agregar rutas con soporte a detección de duplicados
   public function addRouter(string $method, string $path, callable|array $callback, array $middlewares = []): void {
      $normalizedPath = $this->normalizePath($path);
      if (isset($this->routes[$method][$normalizedPath])) {
         throw new \Exception("La ruta {$method} {$normalizedPath} ya existe.");
      }

      $this->routes[$method][$normalizedPath] = [
         'callback' => $callback,
         'middlewares' => $middlewares
      ];
   }

   // Normaliza la ruta para que sea uniforme
   private function normalizePath(string $path): string {
      return $path === '/' ? $path : rtrim($path, '/');
   }

   // Método para agrupar rutas con middlewares heredados
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

         public function patch(string $path, callable|array $callback, array $middlewares = []): void {
            $this->routeCollection->addRouter('PATCH', $path, $callback, array_merge($this->middlewares, $middlewares));
         }

         public function options(string $path, callable|array $callback, array $middlewares = []): void {
            $this->routeCollection->addRouter('OPTIONS', $path, $callback, array_merge($this->middlewares, $middlewares));
         }
      });
   }


   // Método para obtener rutas, con opción de filtrado por método
   public function getRoutes(string $method = null): array {
      if ($method) {
         return $this->routes[$method] ?? [];
      }
      return $this->routes;
   }

   // Agregado: Método para obtener rutas dinámicas con parámetros
   public function getRouteWithParams(string $method, string $path): ?array {
      $normalizedPath = $this->normalizePath($path);
      foreach ($this->routes[$method] ?? [] as $routePath => $routeData) {
         if ($this->matchDynamicRoute($routePath, $normalizedPath)) {
            return $routeData;
         }
      }
      return null;
   }

   // Agregado: Método para obtener rutas estáticas
   private function matchDynamicRoute(string $routePath, string $requestPath): bool {
      $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $routePath);
      return (bool) preg_match('#^' . $pattern . '$#', $requestPath);
   }
}