<?php

/*
 * php-rhino micro-framework
 * Copyright (c) 2019 Luca J
 * Licensed under the MIT license.
 */

/**
 * The Request class holds all relevant information about a received http
 * request and a method to conveniently retrieve http header values.
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
   * Create a new request object which holds all relevant information about
   * a received http request.
   *
   * @param {Array} $options - optional parameter (see api docs)
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
   *
   * @return {string} $header - return the value of a specific header.
   */

  public function get($header) {
    return $this->headers[$header];
  }
}
