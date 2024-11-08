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
      $app['app']['url'] = self::getUrl();
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

   private static function getUrl(): string {
      $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
      $host = $_SERVER['HTTP_HOST'];
      $uri = $protocol . '://' . $host;
      if (!empty($_GET['url'])) {
         $query_string = '';
         if (count($_GET) > 1) {
            $query_string = '?';
            foreach ($_GET as $key => $value) {
               if ($key != 'url') {
                  $query_string .= $key . '=' . $value . '&';
               }
            }
            $query_string = rtrim($query_string, '&');
         }
         $uri .= str_replace($_GET['url'] . $query_string, '', urldecode($_SERVER['REQUEST_URI']));
      } else {
         $uri = $_SERVER['REQUEST_URI'];
      }
      return $uri;
   }

}