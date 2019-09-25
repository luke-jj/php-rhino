<?php

/*
 * php-rhino micro-framework
 * Copyright (c) 2019 Luca J
 * Licensed under the MIT license.
 */

/**
 * Module dependencies.
 * @private
 */

require_once (dirname(__FILE__) . "/request.php");
require_once (dirname(__FILE__) . "/response.php");
require_once (dirname(__FILE__) . "/endresponse.php");
require_once (dirname(__FILE__) . "/router/router.php");
require_once (dirname(__FILE__) . "/middleware/middleware.php");
require_once (dirname(__FILE__) . "/middleware/jsonparse.php");

/**
 * Application is a router that can register middleware and route handlers and
 * mount other routers.
 *
 * The Application class inherits two additional methods. One to start the
 * application by iterating over this applications route $queue and one to
 * generate a new router.
 */

class Application extends Router {

  public function __construct($req, $res, $options = null) {
    parent::__construct($req, $res);

    $this->req->url = $this->removeTrailingSlash($this->req->url);
  }

  /**
   * Start this application.
   *
   * Iterate over all registered route queue collections, match routes and
   * execute middleware and response callback functions.
   */

  public function start() {
    try {
      $this->executeRouter($this);
    } catch (EndResponse $e) {
      // http response has been ended with a `$res.end();` statement
      return;
    }

    $this->res->sendStatus(404);
  }

  /**
   * Return a new router.
   *
   * @return {Router} new router instance generated using this application.
   */

  public function router() {
    return new Router($this->req, $this->res);
  }
}
