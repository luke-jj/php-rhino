<?php

require_once (dirname(__FILE__) . "/request.php");
require_once (dirname(__FILE__) . "/response.php");
require_once (dirname(__FILE__) . "/router/router.php");

class Application extends Router {

  public function __construct($options) {
    parent::__construct();
  }

  public function listen($port = 0) {
  }

  /**
   * Return a new router.
   */

  public function router() {
  }
}
