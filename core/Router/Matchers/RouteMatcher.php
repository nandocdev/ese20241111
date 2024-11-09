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
         return null; // Método HTTP no soportado para ninguna ruta
      }

      foreach ($routes[$method] as $path => $route) {
         $params = $this->matchRoute($path, $uri);
         if ($params !== null) {
            return array_merge($route, ['params' => $params]);
         }
      }

      return null; // No se encontró ninguna ruta que coincida
   }

   private function matchRoute(string $path, string $uri): ?array {
      $pathRegex = $this->buildPathRegex($path);

      // Intenta coincidir la URI con la expresión regular de la ruta
      if (preg_match($pathRegex['regex'], $uri, $matches)) {
         array_shift($matches); // Remueve el primer valor (ruta completa)

         // Combina los nombres de los parámetros con sus valores
         $params = [];
         foreach ($pathRegex['params'] as $index => $name) {
            $params[$name] = isset($matches[$index]) ? $matches[$index] : null;
         }

         return $params;
      }

      return null; // No coincide la URI con el patrón de la ruta
   }

   private function buildPathRegexCP(string $path): array {
      $params = [];

      // Construye una expresión regular a partir del patrón de ruta
      $regex = preg_replace_callback('/\{([a-zA-Z0-9_]+)(?::([^}]+))?\??\}/', function ($matches) use (&$params) {
         $params[] = $matches[1]; // Guarda el nombre del parámetro

         // Utiliza el segundo grupo de la expresión regular para validación personalizada (ej. \d+)
         $paramPattern = $matches[2] ?? '[a-zA-Z0-9_]+';
         return "($paramPattern)";
      }, $path);

      // Retorna la expresión regular y la lista de nombres de parámetros
      return [
         'regex' => "#^" . $regex . "$#",
         'params' => $params,
      ];
   }

   private function buildPathRegex(string $path): array {
      $params = [];

      // Construye una expresión regular a partir del patrón de ruta
      $regex = preg_replace_callback('/\{([a-zA-Z0-9_]+)(?::([^}]+))?\??\}/', function ($matches) use (&$params) {
         $paramName = $matches[1]; // Nombre del parámetro
         $params[] = $paramName; // Guardamos el nombre del parámetro

         // Si hay una validación personalizada, la usamos, sino usamos el patrón predeterminado
         $paramPattern = $matches[2] ?? '[a-zA-Z0-9_]+';

         // Si es opcional, lo marcamos con ? en la expresión regular
         return "($paramPattern)" . ($matches[0][strlen($matches[0]) - 1] === '?' ? '?' : '');
      }, $path);

      // Retorna la expresión regular y la lista de nombres de parámetros
      return [
         'regex' => "#^" . $regex . "$#", // Añade los delimitadores para la expresión regular
         'params' => $params,
      ];
   }

}