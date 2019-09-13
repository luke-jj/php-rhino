<?php

require_once (dirname(__FILE__) . "/../../lib/rhino.php");

$app = rhino();

$app->get('/', function ($req, $res) {
  $res->send("<h1>Hello World</h1>");
});

$app->start();
