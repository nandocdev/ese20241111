<?php
/**
 * @package     core/Router
 * @subpackage  Http
 * @file        Request
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-07 23:53:49
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace ESE\Core\Router\Http;

class Request {
   public string $url;
   public string $base;
   public string $method;
   public string $referer;
   public string $ip;
   public bool $ajax;
   public string $scheme;
   public string $userAgent;
   public string $contentType;
   public int $contentLength;
   public RequestData $data;
   public array $files;
   public array $cookies;
   public array $sessions;
   public array $headers;
   public array $server;
   public bool $secure;
   public string $accept;
   public string $proxyIp;
   public string $host;
   public array $body;
   public array $params;

   public function __construct() {
      $this->server = $_SERVER;
      $this->cookies = $_COOKIE;
      $this->sessions = $_SESSION ?? [];
      $this->files = $_FILES;
      $this->method = $this->determineMethod();
      $this->body = $this->getRequestBody();
      $this->headers = $this->getHeaders();
      $this->scheme = $this->determineScheme();
      $this->url = $this->determineUrl();
      $this->base = $this->determineBaseUrl();
      $this->referer = $this->getServerVar('HTTP_REFERER', '');
      $this->ip = $this->determineIp();
      $this->ajax = $this->isAjaxRequest();
      $this->userAgent = $this->getServerVar('HTTP_USER_AGENT', '');
      $this->contentType = $this->getServerVar('CONTENT_TYPE', '');
      $this->contentLength = (int) $this->getServerVar('CONTENT_LENGTH', 0);
      $this->secure = $this->scheme === 'https';
      $this->accept = $this->getServerVar('HTTP_ACCEPT', '');
      $this->proxyIp = $this->getProxyIp();
      $this->host = $this->getServerVar('HTTP_HOST', '');
      $this->data = new RequestData($this->method);
   }

   private function getRequestBody(): array {
      if ($this->method === 'GET') {
         return $_GET;
      }
      return json_decode(file_get_contents('php://input'), true) ?? [];
   }

   public function addBody(array $data): void {
      $this->body = array_merge($this->body, $data);
   }

   private function getServerVar(string $key, mixed $default = ''): mixed {
      return $this->server[$key] ?? $default;
   }

   private function getHeaders(): array {
      if (function_exists('getallheaders')) {
         return getallheaders();
      }
      $headers = [];
      foreach ($this->server as $name => $value) {
         if (str_starts_with($name, 'HTTP_')) {
            $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
            $headers[$headerName] = $value;
         }
      }
      return $headers;
   }

   private function determineMethod(): string {
      $override = $this->getServerVar('HTTP_X_HTTP_METHOD_OVERRIDE');
      if ($override) {
         return strtoupper($override);
      }
      $req_method = $this->getServerVar('REQUEST_METHOD', 'GET');
      return strtoupper($req_method);
   }

   private function determineScheme(): string {
      if ($this->getServerVar('HTTPS') === 'on' || $this->getServerVar('HTTP_X_FORWARDED_PROTO') === 'https') {
         return 'https';
      }
      return 'http';
   }

   private function determineUrl(): string {
      // return str_replace('@', '%40', $this->getServerVar('REQUEST_URI', '/'));
      $url = $this->getServerVar('REQUEST_URI', '/');
      $url = str_replace('@', '%40', $url);
      return $url;

   }

   private function determineBaseUrl(): string {
      $schemeHost = $this->scheme . '://' . $this->getServerVar('HTTP_HOST', '');
      if (!empty($_GET['url'])) {
         $query = http_build_query(array_diff_key($_GET, ['url' => '']));
         $uri = urldecode($this->getServerVar('REQUEST_URI'));
         $schemeHost .= str_replace($_GET['url'] . ($query ? '?' . $query : ''), '', $uri);
      } else {
         $schemeHost .= $this->getServerVar('REQUEST_URI');
      }
      return $schemeHost;
   }

   private function determineIp(): string {
      foreach (['HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'] as $key) {
         if (!empty($this->server[$key])) {
            return $this->server[$key];
         }
      }
      return '';
   }

   private function isAjaxRequest(): bool {
      return strtolower($this->getServerVar('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest';
   }

   private function getProxyIp(): string {
      foreach (['HTTP_FORWARDED', 'HTTP_X_FORWARDED', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED_FOR_IP', 'VIA', 'X_FORWARDED_FOR', 'FORWARDED_FOR', 'X_FORWARDED_FOR_IP', 'HTTP_PROXY_CONNECTION'] as $key) {
         if (isset($this->server[$key]) && filter_var($this->server[$key], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $this->server[$key];
         }
      }
      return '';
   }
}