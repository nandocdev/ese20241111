<?php
/**
 * @package     srv/esecorp
 * @subpackage  config
 * @file        db.config
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-01 20:17:19
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

return [
   'default' => [
      'driver' => 'mysql',
      'host' => 'localhost',
      'port' => 3306,
      'database' => '',
      'username' => '',
      'password' => '',
      'charset' => 'utf8mb4',
      'collation' => 'utf8mb4_unicode_ci',
      'options' => [
         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
         PDO::ATTR_EMULATE_PREPARES => false,
      ],
   ]
];