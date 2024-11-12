<?php
/**
 * @package     core/Orm
 * @subpackage  Builders
 * @file        QueryBuilder
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 02:08:35
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Orm\Builders;

abstract class QueryBuilder {
   protected string $table;
   protected array $params = [];

   public function __construct(string $table) {
      $this->table = $table;
   }

   // Obtener los parámetros de la consulta
   public function getParams(): array {
      return $this->params;
   }

   abstract public function build(): string;
   abstract public function execute(): array|bool;
}