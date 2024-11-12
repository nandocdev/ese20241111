<?php
/**
 * @package     core/FlexQuery
 * @subpackage  Core
 * @file        Query
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 23:35:00
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\FlexQuery\Core;

use ESE\Core\Orm\Connection\DatabaseConnection;
use ESE\Core\FlexQuery\Core\Structure;


class Query {
   protected DatabaseConnection $connection;
   protected ?Structure $structure;
   protected string $table;
   protected string $prefix = '';
   protected string $separator = '_';
   protected bool $exceptionOnError = false;
   protected bool $convertRead = false;
   protected bool $convertWrite = false;


   function __construct(string $table, DatabaseConnection $connection, ?Structure $structure) {
      $this->table = $table;
      $this->connection = $connection;
      $this->structure = $structure;
   }

   public function setTableName(?string $table = null, string $prefix = '', string $separator = '_'): self {
      if ($table) {
         $this->table = $table;
         $this->prefix = $prefix;
         $this->separator = $separator;
      }
      if ($this->getTableName() == '') {
         throw new \Exception('Table name is required');
      }
      return $this;
   }

   public function getStructure(): ?Structure {
      return $this->structure;
   }

   public function getDatabaseConnection(): DatabaseConnection {
      return $this->connection;
   }

   public function getPrefix(): string {
      return $this->prefix;
   }

   public function getSeparator(): string {
      return $this->separator;
   }

   public function getTable(): string {
      return $this->table;
   }

   public function getTableWithPrefix(): string {
      if ($this->prefix) {
         return $this->prefix . $this->separator . $this->table;
      } else {
         return $this->table;
      }
   }

   public function getTableName(): string {
      if ($this->prefix == '') {
         $table = $this->table;
      } else {
         $table = $this->prefix . $this->separator . $this->table;
      }
      return $table;
   }

   public function throwExceptionOnError(bool $flag): void {
      $this->exceptionOnError = $flag;
   }

   public function convertTypes(bool $read, bool $write): void {
      $this->convertRead = $read;
      $this->convertWrite = $write;
   }

   public function convertReadTypes(bool $flag): void {
      $this->convertRead = $flag;
   }

   public function convertWriteTypes(bool $flag): void {
      $this->convertWrite = $flag;
   }
}