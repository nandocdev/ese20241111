<?php
/**
 * @package     esecorp/core
 * @subpackage  Router
 * @file        Router
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-08 00:20:27
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router;

use ESE\Core\Router\Collection\RouteCollection;
use ESE\Core\Router\Matchers\RouteMatcher;
use ESE\Core\Router\Handler\ControllerInvoker;
use ESE\Core\Router\Handler\MiddlewareHandler;
use ESE\Core\Router\Http\Request;
use ESE\Core\Router\Http\Response;
use Psr\Container\ContainerInterface;

class Router {
   private RouteCollection $routeCollection;
   private RouteMatcher $routeMatcher;
   private ControllerInvoker $controllerInvoker;
   private MiddlewareHandler $middlewareHandler;
   private array $globalMiddlewares = [];
   private ContainerInterface $container;

   public function __construct(
      RouteCollection $routeCollection,
      RouteMatcher $routeMatcher,
      ControllerInvoker $controllerInvoker,
      MiddlewareHandler $middlewareHandler,
      ContainerInterface $container
   ) {
      $this->routeCollection = $routeCollection;
      $this->routeMatcher = $routeMatcher;
      $this->controllerInvoker = $controllerInvoker;
      $this->middlewareHandler = $middlewareHandler;
      $this->container = $container;
   }

   public function addGlobalMiddleware(callable|string $middleware): void {
      $this->globalMiddlewares[] = $middleware;
   }

   public function get(string $path, callable|array $callback, array $middlewares = []): void {
      $this->routeCollection->addRouter('GET', $path, $callback, array_merge($this->globalMiddlewares, $middlewares));
   }

   public function post(string $path, callable|array $callback, array $middlewares = []): void {
      $this->routeCollection->addRouter('POST', $path, $callback, array_merge($this->globalMiddlewares, $middlewares));
   }

   public function put(string $path, callable|array $callback, array $middlewares = []): void {
      $this->routeCollection->addRouter('PUT', $path, $callback, array_merge($this->globalMiddlewares, $middlewares));
   }

   public function delete(string $path, callable|array $callback, array $middlewares = []): void {
      $this->routeCollection->addRouter('DELETE', $path, $callback, array_merge($this->globalMiddlewares, $middlewares));
   }

   public function redirect(string $from, string $to, int $statusCode = 301): void {
      header("Location: {$to}", true, $statusCode);
      exit();
   }

   public function group(callable $callback, array $middlewares = []): void {
      $this->routeCollection->group($callback, array_merge($this->globalMiddlewares, $middlewares));
   }

   public function dispatch(): void {
      $request = new Request();
      $response = new Response();

      try {
         // Match route
         $route = $this->routeMatcher->match($request->method, $request->url, $this->routeCollection);

         if ($route === null) {
            $this->handleRouteNotFound($response);
            return;
         }

         // Handle middlewares
         $this->middlewareHandler->handle($route['middlewares'], $request, $response);
         $request->addBody($route['params']);
         // Invoke controller
         $this->controllerInvoker->invoke($route['callback'], $request, $response);
      } catch (\Exception $e) {
         $this->handleServerError($response, $e);
      }
   }

   private function handleRouteNotFound(Response $response): void {
      $response->setStatus(404)->json(['error' => 'Route not found']);
   }

   private function handleServerError(Response $response, \Exception $e): void {
      $response->setStatus(500)->json(['error' => $e->getMessage()]);
   }
}