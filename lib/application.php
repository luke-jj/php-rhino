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

    $this->req->url = $this->removeTrailingSlash($this->req->url);
  }

  /*
   * Start this application.
   *
   * Iterate over the middleware function collection,
   * and execute middleware functions in an efficient manner.
   */

  public function start() {
    try {
      $this->executeRouter($this);
    } catch (EndResponse $e) {
      // http response has been ended with a `$res.send();` statement
    }
  }

  /**
   * Return a new router.
   */

  public function router() {
    return new Router($this->req, $this->res);
  }
}
