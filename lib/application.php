<?php

require_once (dirname(__FILE__) . "/request.php");
require_once (dirname(__FILE__) . "/response.php");
require_once (dirname(__FILE__) . "/router/router.php");
require_once (dirname(__FILE__) . "/middleware/jsonparse.php");

class Application extends Router {

  public function __construct($req, $res, $options = null) {
    parent::__construct($req, $res);
  }

  /*
   * Start this application.
   *
   * Iterate over the middleware function collection,
   * and execute middleware functions in an efficient manner.
   */

  public function start() {
    $executeMiddleware($this);
  }

  private function executeMiddleware(Router $router) {

    foreach ($this->queue as $middleware) {

      if ($middleware instanceof Router) {
          $this->executeMiddleware($middleware);
      }

      $middleware($this->req, $this->res);
    }
  }

  /**
   * Return a new router.
   */

  public function router() {
      return new Router($req, $res);
  }

  /**
   * Remove - if present - trailing forward slash of this object's resource 
   * location
   */

  private function removeTrailingForwardSlash() {
    $this->req->url = preg_replace("/\/$/", "", $this->req->url);
  }
}
