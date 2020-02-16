<?php

require_once (dirname(__FILE__) . "/../../lib/rhino.php");

$app = rhino();

require_once (dirname(__FILE__) . "/auth.php");
require_once (dirname(__FILE__) . "/routes/documents.php");

$app->use('/documents', $auth, $documentRouter);

$app->get('/hi', $auth, function($req, $res) {
  $res->send("hi there from base.");
});

$app->get('/:number', $auth, function($req, $res) {
  $res->send("You entered " . $req->params['number']);
});

$app->get('/customHeader', function($req, $res) {
  $customHeader = $req->get('X-Custom-Header');
  $res->send("Received $customHeader");
});

$app->start();
