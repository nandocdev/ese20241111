<?php
/**
 * @package     esecorp/core
 * @subpackage  Bootstrap
 * @file        Kernel
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-02 10:28:23
 * @version     1.0.0
 * @description clase donde se definen los metodos que se ejecutan para arrancar la aplicacion
 */
declare(strict_types=1);

namespace ESE\Core\Bootstrap;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use DI\ContainerBuilder;
use ESE\Core\Bootstrap\Config;

class Kernel {
   private static ?Logger $logger = null;
   private static bool $sessionStarted = false;

   private static function autoload() {
      $file = Config::get('path', 'vendor') . '/autoload.php';
      if (!file_exists($file)) {
         throw new \Exception('No se encuentra el archivo de autoload');
      }
      require $file;
   }
   private static function getLogger(): Logger {
      if (!self::$logger) {
         self::$logger = new Logger('ESECorp');
         self::$logger->pushHandler(new StreamHandler(Config::get('path', 'tmp') . '/logs/app.log', Logger::DEBUG));
      }
      return self::$logger;
   }

   private static function boot(bool $useAutowiring = true, string $definitions = 'container.config.php'): \DI\Container {
      $containerBuilder = new ContainerBuilder();
      $containerBuilder->useAutowiring($useAutowiring);
      $containerBuilder->addDefinitions(Config::get('path', 'config') . '/' . $definitions);
      try {
         return $containerBuilder->build();
      } catch (\Exception $e) {
         self::getLogger()->error("Error al construir el contenedor: " . $e->getMessage());
         throw new \Exception('Error al construir el contenedor de dependencias');
      }
   }

   private static function sessions(): void {
      if (self::$sessionStarted)
         return;

      if (session_status() === PHP_SESSION_NONE) {
         ini_set('session.cookie_httponly', 1);
         ini_set('session.use_only_cookies', 1);
         ini_set('session.use_strict_mode', 1);
         session_start();
      }

      self::$sessionStarted = true;
   }

   private static function setupEnvironment(): void {
      date_default_timezone_set(Config::get('app', 'timezone'));
      setlocale(LC_ALL, Config::get('app', 'locale'));
      mb_internal_encoding(Config::get('app', 'charset'));
      mb_http_output(Config::get('app', 'charset'));
      // mb_http_input(Config::get('app', 'charset'));
      mb_language('uni');
      mb_regex_encoding(Config::get('app', 'charset'));
      mb_regex_set_options('u');
   }

   public static function run(): \DI\Container {
      try {
         // self::autoload();
         self::setupEnvironment();
         self::sessions();
         $container = self::boot(false);
         return $container;
      } catch (\Exception $th) {
         self::getLogger()->error("Error al arrancar la aplicacion: " . $th->getMessage());
         throw new \Exception('Error al arrancar la aplicacion ' . $th->getMessage());
      }
   }
}