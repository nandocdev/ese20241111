<?php
/**
 * @package     core/Orm
 * @subpackage  Utils
 * @file        QueryValidator
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 02:26:26
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Orm\Utils;

class QueryValidator {
   public function validateQuery(string $query): bool {
      // Validar que la consulta no esté vacía
      if (empty($query)) {
         throw new \InvalidArgumentException("Query cannot be empty");
      }

      // Se podrían agregar otras validaciones dependiendo de los requerimientos
      // Por ejemplo, evitar la ejecución de ciertas consultas peligrosas
      $this->validateForDangerousKeywords($query);

      return true;
   }

   private function validateForDangerousKeywords(string $query): void {
      $dangerousKeywords = ['DROP', 'TRUNCATE', 'DELETE', 'ALTER'];

      foreach ($dangerousKeywords as $keyword) {
         if (stripos($query, $keyword) !== false) {
            throw new \RuntimeException("Query contains potentially dangerous keyword: {$keyword}");
         }
      }
   }

   public function validateParams(array $params): bool {
      // Validar que los parámetros no estén vacíos
      if (empty($params)) {
         throw new \InvalidArgumentException("Params cannot be empty");
      }

      return true;
   }

   // evalua sql injection
   public function validateInjection(array $params): bool {
      //limpia sql injection
      foreach ($params as $param) {
         if (preg_match('/\b(union|select|from|where|insert|delete|update|drop|truncate|create|alter|exec|execute|declare|xp_cmdshell|sp_|xp_)/i', $param)) {
            throw new \RuntimeException("Query contains potentially dangerous keyword: {$param}");
         }
      }
   }

}