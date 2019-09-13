<?php

/**
 *
 */

class Request {

  public $app;

  public $url;
  public $originalUrl;
  public $baseUrl;

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
    $header = strtolower($header);

    return $header;
  }

}






// class Request {
    // private $http_method;
    // private $resourceLocation;
    // private $http_headers;
    // private $body;
    // private $json;
    // public $query;
    // public $params;

    /**
     * @param $http_method string - the http method in capital letters
     * @param $http_headers array - http headers mapped as keys to values
     * @param $body string - the http request body in string form
     * @param $format string - optional, use 'JSON' to convert the request body
     */
    // function __construct($http_method, $resourceLocation, $http_headers, $body, $format = '') {
        // $this->http_method = strtoupper($http_method);

        // $this->resourceLocation = trim($resourceLocation);
        // $this->removeTrailingForwardSlash();

        // $this->extractQueryStringParameters();

        // $this->http_headers = $http_headers;
        // $this->body = $body;

        // // convert body to json if 'JSON' was given as an argument
        // if ($format === 'JSON') {
            // $this->convertBodyToJson();
        // }
    // }

    /*
     * Getter Methods
     */

    // public function getBody() {
        // return $this->body;
    // }

    // public function getJson() {

        // if ($this->json === null) {
            // $this->convertBodyToJson();
        // }

        // return $this->json;
    // }

    // public function getHttpMethod() {
        // return $this->http_method;
    // }

    // public function getHttpHeaders() {
        // return $this->http_headers;
    // }

    // public function getResourceLocation() {
        // return $this->resourceLocation;
    // }

    /**
     * Return true if the request body is valid json.
     */

    // public function hasValidJson() {

        // if ($this->json === null) {
            // $this->convertBodyToJson();
        // }

        // if ($this->json === null) {
            // return false;
        // }

        // return true;
    // }

    /**
     * Convert the request body to json.
     *
     * The json attribute will be null if conversion failed.
     * If the request body is empty the json attribute will be an empty array.
     */

    // private function convertBodyToJson() {
        // if (!empty($this->body)) {
            // $this->json = json_decode($this->body, true);
        // } else {
            // $this->json = array();
        // }
    // }

    /**
     * Remove - if present - trailing forward slash of this object's resource
     * location
     */

    // private function removeTrailingForwardSlash() {
        // $this->resourceLocation = preg_replace(
            // "/\/$/",
            // "",
            // $this->resourceLocation);
    // }

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
// }
