<?php
/**
 * @package     esecorp/core
 * @subpackage  Bootstrap
 * @file        Config
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-02 10:18:58
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Bootstrap;

class Config {
   private static $config = [];
   private static function load(string $file): array {
      $path = dirname(__DIR__, 2) . '/config/' . $file . '.config.php';
      if (file_exists($path)) {
         return require $path;
      }
      return [];
   }

   public static function get(string $key, string $subkey): mixed {
      $app = self::load('app');
      $db = self::load('db');
      $config = array_merge($app, $db);
      if (array_key_exists($key, $config)) {
         if (array_key_exists($subkey, $config[$key])) {
            return $config[$key][$subkey];
         }
         return $config[$key];
      }
      return null;
   }

}