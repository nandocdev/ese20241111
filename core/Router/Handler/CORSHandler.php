<?php
/**
 * @package     core/Router
 * @subpackage  Handler
 * @file        CORSHandler
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 00:33:00
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router\Handler;

use ESE\Core\Router\Http\Request;
use ESE\Core\Router\Http\Response;

class CORSHandler {
   public function __invoke(Request $request, Response $response): void {
      $response->addHeader('Access-Control-Allow-Origin', '*');
      $response->addHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
      $response->addHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
   }
}