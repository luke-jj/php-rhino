<?php

class Response {

  public $app;
  public $headersSent = false;

  public function status($code) {
    http_response_code($code);

    return $this;
  }

  public function sendStatus($code) {
    $this->status($code);
    $this->send($code . " - " . $this->getHttpStatusMessage($code));

    return $this;
  }

  public function end() {
    throw new EndResponse();
  }

  public function send($body) {
    echo $body;

    return $this;
  }

  public function json($body) {
    $json = json_encode($body);
    header('Content-Type: application/json');
    $this->send($json);

    return $this;
  }

  public function set($header, $value) {

    return $this;
  }

  public function type($type) {

    return $this;
  }

  private function getHttpStatusMessage($code){
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
