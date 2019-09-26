<?php

/*
 * php-rhino micro-framework
 * Copyright (c) 2019 Luca J
 * Licensed under the MIT license.
 */

/**
 * The Response class provides useful methods to set http response codes, write
 * to the http response stream, and to break out of the request response cycle
 * by ending the http response.
 */

class Response {

  public $app;
  public $headersSent = false;

  /**
   * Set the http status code to the given value.
   *
   * @param $code {int} http status code
   */

  public function status($code) {
    http_response_code($code);

    return $this;
  }

  /**
   * Set the http status code to the given value and write the conventional
   * status message to the response body.
   *
   * @param $code {int} http status code
   */

  public function sendStatus($code) {
    $this->status($code);
    $this->send($code . " - " . $this->getHttpStatusMessage($code));

    return $this;
  }

  /**
   * End the http response by throwing a new EndResponse Exception.
   * The http output stream closes when this function is called.
   */

  public function end() {
    throw new EndResponse();
  }

  /**
   * Write a given string to the http response stream.
   *
   * @param $body {string} text to write to the http response body.
   */

  public function send($body) {
    echo $body;

    return $this;
  }

  /**
   * Convert the given argument to a json encoded string, set the
   * 'Content-Type' response header to 'application/json' and write the json
   * string to the http response stream.
   *
   * @param $body {mixed} - object to be converted to json string (any type)
   */

  public function json($body) {
    $json = json_encode($body);
    header('Content-Type: application/json');
    $this->send($json);

    return $this;
  }

  /**
   * Set an http response header to a given value. This function can not be
   * used after the http response body has been written to.
   *
   * @param $header {string} http header
   * @param $value {string} http header value
   */

  public function set($header, $value) {
    header($header . ": " . $value);

    return $this;
  }

  /**
   * Return the conventional status message for a given http status code.
   *
   * @param $code {int} http status code
   * @private
   */

  private function getHttpStatusMessage($code) {
    $status = array(
      100 => 'Continue',
      101 => 'Switching Protocols',
      200 => 'OK',
      201 => 'Created',
      202 => 'Accepted',
      203 => 'Non-Authoritative Information',
      204 => 'No Content',
      205 => 'Reset Content',
      206 => 'Partial Content',
      300 => 'Multiple Choices',
      301 => 'Moved Permanently',
      302 => 'Found',
      303 => 'See Other',
      304 => 'Not Modified',
      305 => 'Use Proxy',
      306 => '(Unused)',
      307 => 'Temporary Redirect',
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      406 => 'Not Acceptable',
      407 => 'Proxy Authentication Required',
      408 => 'Request Timeout',
      409 => 'Conflict',
      410 => 'Gone',
      411 => 'Length Required',
      412 => 'Precondition Failed',
      413 => 'Request Entity Too Large',
      414 => 'Request-URI Too Long',
      415 => 'Unsupported Media Type',
      416 => 'Requested Range Not Satisfiable',
      417 => 'Expectation Failed',
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      502 => 'Bad Gateway',
      503 => 'Service Unavailable',
      504 => 'Gateway Timeout',
      505 => 'HTTP Version Not Supported');

    return ($status[$code]) ? $status[$code] : "";
  }
}
