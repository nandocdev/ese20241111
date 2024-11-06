<?php
/**
 * @package     srv/esecorp
 * @subpackage  config
 * @file        app.config
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-01 20:17:07
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

return [
   'app' => [
      'name' => 'ESECorp',
      'version' => '1.0.0',
      'description' => 'Enterprise Software Engineering Corporation',
      'author' => 'Fernando Castillo <ferncastillov@outlook.com>',
      'timezone' => 'America/Panama',
      'locale' => 'es_PA',
      'charset' => 'UTF-8',
      'env' => 'development',
      'debug' => true,
      'methods_allowed' => ['GET', 'POST', 'PUT', 'DELETE'],
   ],
   'path' => [
      'root' => dirname(__DIR__),
      'app' => dirname(__DIR__) . '/app',
      'config' => dirname(__DIR__) . '/config',
      'public' => dirname(__DIR__) . '/public',
      'tmp' => dirname(__DIR__) . '/tmp',
      'vendor' => dirname(__DIR__) . '/vendor',
   ],
   'mail' => [
      'driver' => 'smtp',
      'host' => 'smtp.gmail.com',
      'port' => 587,
      'username' => '',
      'password' => '',
      'encryption' => 'tls',
      'from' => '',
      'from_name' => '',
   ],
   'system' => [
      'module' => 'home',
      'controller' => 'home',
      'action' => 'index',
      'breadcrumbs' => true,
      'namespace' => 'ESE\\App\\Modules\\',
   ],
   'hash' => [
      'algo' => PASSWORD_BCRYPT,
      'cost' => 10,
      'seed' => sha1('ESECorp'),
   ],
   'session' => [
      'name' => 'session_ESECorp',
      'notify' => 'notify_ESECorp',
      'lifetime' => 3600,
      'path' => '/',
      'domain' => null,
      'secure' => false,
      'httponly' => true,
   ],
];