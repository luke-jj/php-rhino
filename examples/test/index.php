<?php

require_once (dirname(__FILE__) . "/../../lib/rhino.php");

$app = rhino();

// $app->use($jsonparse);

$documentRouter = $app->router();

$documentRouter->get('/', function($req, $res) {
  $res->send("Hello from DocumentRouter");
});

$documentRouter->get('/today', function($req, $res) {
  $res->send("Hello from DocumentRouter today");
});

$app->use('/documents', $documentRouter);

$app->start();
