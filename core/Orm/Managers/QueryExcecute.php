<?php
/**
 * @package     core/Orm
 * @subpackage  Managers
 * @file        QueryExcecute
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-10 01:18:48
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Orm\Managers;

use ESE\Core\Orm\Connection\DatabaseConnection;

class QueryExcecute {
   private \PDO $connection;

   public function __construct(DatabaseConnection $connection) {
      $this->connection = $connection->getConnection();
   }

   public function executeTransaction(string $sql, array $params): bool {
      try {
         $this->connection->beginTransaction();
         $stmt = $this->connection->prepare($sql);
         $stmt->execute($params);
         $rows = $stmt->rowCount();
         $this->connection->commit();
         return $rows > 0;
      } catch (\PDOException $e) {
         $this->connection->rollBack();
         return false;
      }
   }

   public function executeQuery(string $sql, array $params): array|bool {
      try {
         $stmt = $this->connection->prepare($sql);
         $stmt->execute($params);
         return $stmt->fetchAll(\PDO::FETCH_ASSOC);

      } catch (\PDOException $e) {
         return false;
      }
   }
}