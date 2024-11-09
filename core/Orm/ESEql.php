<?php
/**
 * @package     esecorp/core
 * @subpackage  Orm
 * @file        ESEql
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 02:28:23
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Orm;

use ESE\Core\Orm\Builders\DeleteQueryBuilder;
use ESE\Core\Orm\Builders\InsertQueryBuilder;
use ESE\Core\Orm\Builders\SelectQueryBuilder;
use ESE\Core\Orm\Builders\UpdateQueryBuilder;


class ESEql {
   private string $table;

   public function __construct(string $table) {
      $this->table = $table;
   }

   public function select(): SelectQueryBuilder {
      return new SelectQueryBuilder($this->table);
   }

   public function insert(): InsertQueryBuilder {
      return new InsertQueryBuilder($this->table);
   }

   public function update(): UpdateQueryBuilder {
      return new UpdateQueryBuilder($this->table);
   }

   public function delete(): DeleteQueryBuilder {
      return new DeleteQueryBuilder($this->table);
   }
}

// $query = new ESEql('users');
// $query->select()->where('id', '=', 1)->build();