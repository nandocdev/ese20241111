<?php
/**
 * @package     core/Orm
 * @subpackage  Builders
 * @file        DeleteQueryBuilder
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 02:14:49
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Orm\Builders;
use ESE\Core\Orm\Builders\QueryBuilder;
use ESE\Core\Orm\Managers\QueryExcecute;
use ESE\Core\Orm\Connection\DatabaseConnection;

class DeleteQueryBuilder extends QueryBuilder {
   private array $where = [];
   private DatabaseConnection $connection;

   public function __construct(string $table) {
      parent::__construct($table);
      $this->connection = new DatabaseConnection();
   }

   public function where(string $column, string $operator, $value): self {
      $this->where[] = "{$column} {$operator} ?";
      $this->params[] = $value;
      return $this;
   }

   public function build(): string {
      $whereClause = implode(' AND ', $this->where);
      return "DELETE FROM {$this->table} WHERE {$whereClause}";
   }

   public function execute(): bool {
      $sql = $this->build();
      $query = new QueryExcecute(new DatabaseConnection());
      return $query->executeTransaction($sql, $this->params);
   }
}