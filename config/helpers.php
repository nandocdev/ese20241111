<?php
/**
 * @package     srv/esecorp
 * @subpackage  config
 * @file        helpers
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-01 08:34:26
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

// definicion de helpers
return [
   "app_name" => "srv/esecorp",
   // obtiene variables de entorno
   "env" => function ($key, $default = null) {
      return $_ENV[$key] ?? $default;
   },
];