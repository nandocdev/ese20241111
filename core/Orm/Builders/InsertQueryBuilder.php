<?php
/**
 * @package     core/Orm
 * @subpackage  Builders
 * @file        InsertQueryBuilder
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 02:16:05
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Orm\Builders;
use ESE\Core\Orm\Builders\QueryBuilder;

class InsertQueryBuilder extends QueryBuilder {
   private array $columns = [];
   private array $values = [];

   // Método insert que acepta tanto un solo par clave-valor como múltiples pares
   public function insert($keys, $values = null): self {
      // Si solo se pasa un valor como clave y un valor como valor (par clave-valor)
      if (is_string($keys) && $values !== null) {
         $keys = [$keys];
         $values = [$values];
      }

      // Verifica que ambos arrays tengan el mismo tamaño
      if (count($keys) !== count($values)) {
         throw new \InvalidArgumentException('Keys and values must have the same number of elements');
      }

      // Almacena las claves y valores para esta inserción
      $this->columns = array_merge($this->columns, $keys);
      $this->values = array_merge($this->values, $values);

      return $this;  // Retorna $this para permitir encadenamiento
   }

   public function build(): string {
      // Ordenar las claves y valores (si es necesario)
      $columns = implode(', ', $this->columns);
      $placeholders = implode(', ', array_fill(0, count($this->values), '?'));

      // Construir la consulta SQL
      $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

      // Establecer los parámetros para la ejecución
      $this->params = $this->values;

      return $sql;
   }
}