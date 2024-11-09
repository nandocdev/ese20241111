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
class DeleteQueryBuilder extends QueryBuilder {
   private array $where = [];

   public function where(string $column, string $operator, $value): self {
      $this->where[] = "{$column} {$operator} ?";
      $this->params[] = $value;
      return $this;
   }

   public function build(): string {
      $whereClause = implode(' AND ', $this->where);
      return "DELETE FROM {$this->table} WHERE {$whereClause}";
   }
}