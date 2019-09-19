<?php

/**
 *
 */

class Request {

  public $app;
  public $originalUrl;
  public $url;
  public $queryString;
  public $headers;
  public $method;
  public $body;
  public $params;
  public $query;
  public $hostname;
  public $port;

  /**
   *
   */

  public function __construct($options = null) {
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->headers = getallheaders();

    if (isset($_SERVER['PATH_INFO'])) {
      $this->originalUrl = $_SERVER['PATH_INFO'];
    } else {
      $this->originalUrl = '/';
    }

    $this->url = $this->originalUrl;
    $this->queryString = '';
    $this->query = array();

    if (isset($_SERVER['QUERY_STRING'])) {
      $this->queryString = $_SERVER['QUERY_STRING'];
      parse_str($this->queryString, $this->query);
    }

    $this->body = file_get_contents('php://input');
    $this->hostname = $_SERVER['SERVER_NAME'];
    $this->port = $_SERVER['SERVER_PORT'];
  }

  /**
   * Returns the specified HTTP request header field.
   */

  public function get($header) {
    return $this->headers[$header];
  }
}
