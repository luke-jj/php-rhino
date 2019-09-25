<?php

/*
 * php-rhino micro-framework
 * Copyright (c) 2019 Luca J
 * Licensed under the MIT license.
 *
 * Require this file in your index.php file to get started and call the
 * `rhino()` method to instantiate a new instance of this framework's main
 * Application class.
 */

require_once (dirname(__FILE__) . "/application.php");

/**
 * Generate the request and response objects used throughout the application
 * and return a new instance of this framework's main Application class.
 *
 * @param $options Array - optional parameter mapping of settings (see api docs)
 * @return Application
 */

function rhino($options = null) {

  $req = new Request($options);
  $res = new Response($options);

  $app = new Application($req, $res, $options);
  $app->req->app = $app;
  $app->res->app = $app;

  return $app;
}
