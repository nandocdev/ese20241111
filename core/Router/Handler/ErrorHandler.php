<?php
/**
 * @package     core/Router
 * @subpackage  Handler
 * @file        ErrorHandler
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 00:42:05
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router\Handler;

use ESE\Core\Router\Http\Response;

class ErrorHandler {
   public function handleError(\Throwable $e, Response $response): void {
      // Manejo básico de excepciones
      if ($e instanceof \NotFoundHttpException) {
         $response->setStatus(404)->json(['error' => 'Not Found', 'message' => $e->getMessage()]);
      } elseif ($e instanceof \UnauthorizedHttpException) {
         $response->setStatus(401)->json(['error' => 'Unauthorized', 'message' => $e->getMessage()]);
      } else {
         // Error general
         $response->setStatus(500)->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()]);
      }
   }
}