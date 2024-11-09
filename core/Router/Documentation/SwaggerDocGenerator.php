<?php
/**
 * @package     core/Router
 * @subpackage  Middlewares
 * @file        SwaggerDocGenerator
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 00:39:57
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router\Documentation;

use ESE\Core\Router\Collection\RouteCollection;

class SwaggerDocGenerator {
    public function generate(RouteCollection $routeCollection): string {
        $routes = $routeCollection->getRoutes();
        $swaggerDoc = ['paths' => []];

        foreach ($routes as $method => $paths) {
            foreach ($paths as $path => $route) {
                $swaggerDoc['paths'][$path][$method] = [
                    'summary' => $route['callback'],
                    'parameters' => $this->extractParameters($path),
                ];
            }
        }

        return json_encode($swaggerDoc);
    }

    private function extractParameters(string $path): array {
        preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $path, $matches);
        return array_map(fn($param) => ['name' => $param, 'in' => 'path'], $matches[1]);
    }
}