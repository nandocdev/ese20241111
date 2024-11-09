<?php
/**
 * @package     esecorp/app
 * @subpackage  Controllers
 * @file        HomeController
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-08 01:19:55
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\App\Controllers;

class HomeController {
   public static function index($request, $response) {
      $response->json(['message' => 'Hello, World!', 'status' => 200]);
   }
}