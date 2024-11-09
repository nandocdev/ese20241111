<?php
/**
 * @package     core/Orm
 * @subpackage  Utils
 * @file        SqlHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 02:28:03
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Orm\Utils;

class SqlHelper {
   public static function buildWhere(array $conditions): string {
      if (empty($conditions)) {
         return '';
      }

      $whereClauses = array_map(fn($condition) => "($condition)", $conditions);
      return ' WHERE ' . implode(' AND ', $whereClauses);
   }

   public static function buildOrderBy(array $order): string {
      if (empty($order)) {
         return '';
      }

      $orderClauses = array_map(fn($field, $direction) => "{$field} {$direction}", array_keys($order), $order);
      return ' ORDER BY ' . implode(', ', $orderClauses);
   }

   public static function buildLimit(int $limit, int $offset = 0): string {
      return " LIMIT {$limit} OFFSET {$offset}";
   }

   public static function escapeIdentifier(string $identifier): string {
      return "`{$identifier}`"; // En caso de ser MySQL
   }
}