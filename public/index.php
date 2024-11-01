<?php
/**
 * @package     srv/esecorp
 * @subpackage  public
 * @file        index
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-01 08:39:54
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

// definicion de helpers
$helpers = require __DIR__ . '/../config/helpers.php';

// obtiene variables de entorno
$env = $helpers['env'];

// imprime
echo $env('app_name', "srv/esecorp");