<?php

require_once (dirname(__FILE__) . "/request.php");
require_once (dirname(__FILE__) . "/response.php");
require_once (dirname(__FILE__) . "/endresponse.php");
require_once (dirname(__FILE__) . "/router/router.php");
require_once (dirname(__FILE__) . "/middleware/middleware.php");
require_once (dirname(__FILE__) . "/middleware/jsonparse.php");

class Application extends Router {

  public function __construct($req, $res, $options = null) {
    parent::__construct($req, $res);

    if (!$options['strict']) {
      $this->req->url = preg_replace("/\/$/", "", $this->req->url);
    }
  }

  /*
   * Start this application.
   *
   * Iterate over the middleware function collection,
   * and execute middleware functions in an efficient manner.
   */

  public function start() {
    $this->executeMiddleware();
  }

  private function executeMiddleware() {

    foreach ($this->queue as $middleware) {

      // TODO: fix! currently not working
      if ($middleware instanceof Router) {
        $this->executeMiddleware($middleware);
      }

      ($middleware) ($this->mountpath, $this->req, $this->res);
    }
  }

  /**
   * Return a new router.
   */

  public function router() {
    return new Router($this->req, $this->res);
  }
}
