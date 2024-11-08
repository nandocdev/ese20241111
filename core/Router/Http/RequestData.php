<?php
/**
 * @package     core/Router
 * @subpackage  Http
 * @file        RequestData
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-07 23:45:27
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router\Http;

class RequestData {
   private string $method;
   private array $data;
   private array $files;

   public function __construct(string $method) {
      $this->method = strtoupper($method);
      $this->data = $this->getData();
      $this->files = $_FILES;
   }

   /**
    * Obtener datos de la solicitud según el método y tipo de contenido.
    */
   private function getData(): array {
      $data = [];
      $methodsAvailable = ['POST', 'PUT', 'DELETE'];

      if ($this->method === 'GET') {
         $data = $_GET;
      } elseif (in_array($this->method, $methodsAvailable)) {
         $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

         if (strpos($contentType, 'application/json') !== false) {
            $jsonInput = file_get_contents('php://input');
            $data = json_decode($jsonInput, true) ?: [];
         } else {
            $data = $_POST;
         }
      }

      // Limpiar todos los valores de entrada.
      return array_map([$this, 'cleanInput'], $data);
   }

   /**
    * Limpia la entrada para evitar inyecciones de scripts y SQL.
    */
   private function cleanInput($input): mixed {
      if (is_array($input)) {
         return array_map([$this, 'cleanInput'], $input);
      }

      // Limpieza básica de scripts y etiquetas
      $output = htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
      return filter_var($output, FILTER_SANITIZE_STRING);
   }

   /**
    * Obtener solo los parámetros especificados.
    */
   public function only(array $params): array {
      return array_filter($this->data, fn($key) => in_array($key, $params), ARRAY_FILTER_USE_KEY);
   }

   /**
    * Excluir los parámetros especificados.
    */
   public function except(array $params): array {
      return array_filter($this->data, fn($key) => !in_array($key, $params), ARRAY_FILTER_USE_KEY);
   }

   /**
    * Agregar datos adicionales al arreglo de datos.
    */
   public function add(array $data): void {
      $this->data = array_merge($this->data, $data);
   }

   /**
    * Obtener un valor de la solicitud con un valor predeterminado.
    */
   public function get(string $key, mixed $default = null): mixed {
      return $this->data[$key] ?? $default;
   }

   /**
    * Comprobar si un parámetro existe en la solicitud.
    */
   public function has(string $key): bool {
      return isset($this->data[$key]);
   }

   /**
    * Obtener todos los datos de la solicitud.
    */
   public function all(): array {
      return $this->data;
   }

   /**
    * Comprobar si un archivo fue subido y existe.
    */
   public function isFile(string $key): bool {
      return isset($this->files[$key]) && is_uploaded_file($this->files[$key]['tmp_name']);
   }

   /**
    * Obtener todos los archivos de la solicitud.
    */
   public function getFiles(): array {
      return $this->files;
   }

   /**
    * Obtener un archivo específico de la solicitud.
    */
   public function file(string $key): ?array {
      return $this->files[$key] ?? null;
   }

}