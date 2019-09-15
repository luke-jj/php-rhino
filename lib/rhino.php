<?php

require_once (dirname(__FILE__) . "/application.php");

function rhino($options = null) {

  $req = new Request($options);
  $res = new Response($options);

  $app = new Application($req, $res, $options);
  // $app->req->app = $app;
  // $app->res->app = $app;

  return $app;
}
