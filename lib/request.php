<?php

/**
 *
 */

class Request {

  public function __construct() {
  }

  public $app;

  public $originalUrl; // the original url, don't change
  public $url; // used for internal routing purposes
  public $baseUrl; // base url => "/users?sort=desc"
  public $path;  // resource path => "/users", from example.com/users?sort=desc
  public $queryString; // "?sort=desc&limit=50"

  // $_SERVER['PATH_INFO'];
  // $_SERVER['ORIG_PATH_INFO'];
  // $_SERVER['QUERY_STRING'];
  // $_SERVER['REQUEST_METHOD'];

  public $headers;
  public $method;

  public $body;
  public $params;
  public $query;

  /**
   * Returns the specified HTTP request header field.
   */

  public function get($header) {
    return $this->headers[$header];
  }

  /**
   * Extract query string parameters and assign them in map form to the
   * `$query` member of this object
   */

  // private function extractQueryStringParameters() {
    // $this->query = array();

    // if (preg_match("/\?/", $this->resourceLocation)) {
      // $queryString = preg_split("/\?/", $this->resourceLocation);
      // $this->resourceLocation = array_shift($queryString);

      // $queryParameters = preg_split("/&/", $queryString[0]);

      // foreach ($queryParameters as $queryParameter) {
        // $queryParameter = urldecode($queryParameter);
        // $keyValuePair = preg_split("/=/", $queryParameter);
        // $this->query[$keyValuePair[0]] = $keyValuePair[1];
      // }
    // }
  // }
}
