<?php

class Response {

  public $app;
  public $headersSent = false;

  public function status($code) {
    if ($this->headersSent) {
      return;
    }

    $headersSent = true;
  }

  public function sendStatus($code) {
  }

  public function end() {
  }

  public function send() {
  }

  public function json() {
  }

  public function set($header, $value) {
  }

  public function type($type) {
  }
}
