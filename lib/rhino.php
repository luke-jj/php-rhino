<?php

require_once (dirname(__FILE__) . "/application.php");

function rhino($options = null) {

  $req = new Request();
  $res = new Response();

  $req->method = $_SERVER['REQUEST_METHOD'];
  $req->headers = getallheaders();

  $app = new Application($req, $res, $options);
  $app->req->app = $app;
  $app->res->app = $app;

  return $app;
}
