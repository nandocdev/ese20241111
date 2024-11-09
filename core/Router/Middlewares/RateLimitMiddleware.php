<?php
/**
 * @package     core/Router
 * @subpackage  Middlewares
 * @file        RateLimitMiddleware
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 00:34:52
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router\Middlewares;
use ESE\Core\Router\Http\Request;
use ESE\Core\Router\Http\Response;

class RateLimitMiddleware {
   private int $limit;
   private int $window;

   public function __construct(int $limit = 100, int $window = 3600) {
      $this->limit = $limit;
      $this->window = $window;
   }

   public function __invoke(Request $request, Response $response): void {
      // Lógica de verificación de rate limit
      $ip = $request->ip;
      $key = "rate_limit_{$ip}"; // Por IP
      $requestCount = $this->getRequestCount($key);

      if ($requestCount >= $this->limit) {
         $response->setStatus(429)->json(['error' => 'Too Many Requests']);
         return;
      }

      $this->incrementRequestCount($key);
   }

   // Obtiene el número de solicitudes realizadas
   private function getRequestCount(string $key): int {
      return 0;
   }

   // Incrementa el contador de solicitudes
   private function incrementRequestCount(string $key): void {

   }
}