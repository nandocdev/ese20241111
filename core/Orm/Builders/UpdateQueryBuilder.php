<?php
/**
 * @package     core/Orm
 * @subpackage  Builders
 * @file        UpdateQueryBuilder
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 02:12:00
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Orm\Builders;
use ESE\Core\Orm\Builders\QueryBuilder;
use ESE\Core\Orm\Managers\QueryExcecute;
use ESE\Core\Orm\Connection\DatabaseConnection;

class UpdateQueryBuilder extends QueryBuilder {
   private array $set = [];
   private array $where = [];
   private DatabaseConnection $connection;

   public function __construct(string $table) {
      parent::__construct($table);
      $this->connection = new DatabaseConnection();
   }

   public function set(string $column, $value): self {
      $this->set[] = "{$column} = ?";
      $this->params[] = $value;
      return $this;
   }

   public function where(string $column, string $operator, $value): self {
      $this->where[] = "{$column} {$operator} ?";
      $this->params[] = $value;
      return $this;
   }

   public function build(): string {
      $setClause = implode(', ', $this->set);
      $whereClause = implode(' AND ', $this->where);

      $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$whereClause}";
      return $sql;
   }

   public function execute(): bool {
      $sql = $this->build();
      $query = new QueryExcecute($this->connection);
      return $query->executeTransaction($sql, $this->params);
   }

}