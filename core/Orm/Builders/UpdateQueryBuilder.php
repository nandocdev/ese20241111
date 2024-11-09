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

class UpdateQueryBuilder extends QueryBuilder {
   private array $set = [];
   private array $where = [];

   // public function set_cp(array $values): self {
   //    foreach ($values as $column => $value) {
   //       $this->set[] = "{$column} = ?";
   //       $this->params[] = $value;
   //    }
   //    return $this;
   // }

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
}