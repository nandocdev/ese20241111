<?php
/**
 * @package     core/Orm
 * @subpackage  Handlers
 * @file        QueryExecutor
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 02:25:09
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Orm\Handlers;

use ESE\Core\Orm\Connection\DatabaseConnection;
use PDOException;


class QueryExecutor {
   private DatabaseConnection $dbConnection;

   public function __construct(DatabaseConnection $dbConnection) {
      $this->dbConnection = $dbConnection;
   }

   public function executeQuery(string $query, array $params = []): bool {
      try {
         $pdo = $this->dbConnection->getConnection();
         $stmt = $pdo->prepare($query);
         return $stmt->execute($params);
      } catch (PDOException $e) {
         throw new \RuntimeException("Query execution failed: " . $e->getMessage());
      }
   }

   public function fetchAll(string $query, array $params = []): array {
      try {
         $pdo = $this->dbConnection->getConnection();
         $stmt = $pdo->prepare($query);
         $stmt->execute($params);
         return $stmt->fetchAll();
      } catch (PDOException $e) {
         throw new \RuntimeException("Query execution failed: " . $e->getMessage());
      }
   }

   public function fetchOne(string $query, array $params = []): ?array {
      try {
         $pdo = $this->dbConnection->getConnection();
         $stmt = $pdo->prepare($query);
         $stmt->execute($params);
         return $stmt->fetch();
      } catch (PDOException $e) {
         throw new \RuntimeException("Query execution failed: " . $e->getMessage());
      }
   }
}