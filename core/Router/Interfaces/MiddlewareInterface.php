<?php
/**
 * @package     core/Router
 * @subpackage  Interfaces
 * @file        MiddlewareInterface
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 00:01:31
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router\Interfaces;
use ESE\Core\Router\Http\Request;
use ESE\Core\Router\Http\Response;

interface MiddlewareInterface {
   public function handle(Request $request, Response $response, callable $next): void;
}