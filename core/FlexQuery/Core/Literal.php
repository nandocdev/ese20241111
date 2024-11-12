<?php
/**
 * @package     core/FlexQuery
 * @subpackage  Core
 * @file        Literal
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-09 23:34:42
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\FlexQuery\Core;

class Literal {

   private string $value;

   public function __construct(string $value) {
      $this->value = $value;
   }

   public function __toString(): string {
      return $this->value;
   }

}