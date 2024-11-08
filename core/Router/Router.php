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

class Router {
   private RouteCollection $routeCollection;
   private RouteMatcher $routeMatcher;
   private ControllerInvoker $controllerInvoker;
   private MiddlewareHandler $middlewareHandler;
   private array $globalMiddlewares = [];

   public function __construct() {
      $this->routeCollection = new RouteCollection();
      $this->routeMatcher = new RouteMatcher();
      $this->controllerInvoker = new ControllerInvoker();
      $this->middlewareHandler = new MiddlewareHandler();
   }

   public function addGlobalMiddleware(callable $middleware): void {
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

   public function group(callable $callback, array $middlewares = []): void {
      $this->routeCollection->group($callback, array_merge($this->globalMiddlewares, $middlewares));
   }

   public function dispatch(): void {
      $request = new Request();
      $response = new Response();


      $route = $this->routeMatcher->match($request->method, $request->url, $this->routeCollection);

      if ($route === null) {

         $response->setStatus(404)->json(['error_route' => 'Route not found']);
         return;
      }

      $this->middlewareHandler->handle($route['middlewares'], $request, $response);
      $this->controllerInvoker->invoke($route['callback'], $request, $response);
   }
}