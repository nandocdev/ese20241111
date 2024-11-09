<?php
/**
 * @package     core/Router
 * @subpackage  Matchers
 * @file        RouteParameterExtractor
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 00:43:02
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router\Matchers;

class RouteParameterExtractor {
   public function extractParams(string $uri, string $pathPattern): ?array {
      $pathRegex = preg_replace('/\{([a-zA-Z0-9_]+)\??\}/', '([a-zA-Z0-9_]+)?', $pathPattern);
      $pathRegex = "#^" . $pathRegex . "$#";
      if (preg_match($pathRegex, $uri, $matches)) {
         array_shift($matches); // Eliminar la ruta completa
         return $matches;
      }
      return null;
   }
}