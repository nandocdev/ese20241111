<?php
/**
 * @package     core/Orm
 * @subpackage  Connection
 * @file        DatabaseConnection
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 01:50:10
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\FlexQuery\Connection;
use PDO;
use PDOException;
use ESE\Core\Bootstrap\Config;

class DatabaseConnection {

   private ?PDO $connection = null;
   private string $driver;
   private string $host;
   private int $port;
   private string $dbname;
   private string $user;
   private string $password;
   private string $charset;
   private array $options;

   public function __construct(string $type = 'default') {
      $this->driver = Config::get($type, 'driver');
      $this->host = Config::get($type, 'host');
      $this->port = Config::get($type, 'port');
      $this->dbname = Config::get($type, 'database');
      $this->user = Config::get($type, 'username');
      $this->password = Config::get($type, 'password');
      $this->charset = Config::get($type, 'charset');
      $this->options = Config::get($type, 'options');
   }

   private function connect(): void {
      $dsn = "";
      try {
         switch ($this->driver) {
            case 'mysql':
               $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}";
               break;
            case 'pgsql':
               $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};user={$this->user};password={$this->password}";
               break;
            case 'sqlite':
               $dsn = "sqlite:{$this->dbname}";
               break;
            case 'sqlsrv':
               $dsn = "sqlsrv:Server={$this->host},{$this->port};Database={$this->dbname}";
               break;
            default:
               throw new \InvalidArgumentException("Driver not supported: {$this->driver}");
         }
         $this->connection = new PDO($dsn, $this->user, $this->password, $this->options);
      } catch (PDOException $e) {
         throw new PDOException("Error connecting to database: {$e->getMessage()}");
      }
   }

   // Obtener la conexión a la base de datos
   public function getConnection(): PDO {
      if ($this->connection === null) {
         $this->connect();
      }
      return $this->connection;
   }

   // Cerrar la conexión (si es necesario)
   public function close(): void {
      $this->connection = null;
   }

}