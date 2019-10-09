<?php

require_once (dirname(__FILE__) . "/../../lib/rhino.php");

$app = rhino();

// $app->use($jsonparse, function($req, $res) {
  // $res->send("This is the second callback.");
// }, function ($req, $res) {
  // $res->send("This is the third callback.");
// });

require_once (dirname(__FILE__) . "/auth.php");
require_once (dirname(__FILE__) . "/routes/documents.php");
$app->use('/documents', $auth, $auth, $documentRouter, $auth);

$app->get('/hi', $auth, $auth, function($req, $res) {
  $res->send("hi there from base.");
});

$app->get('/hi', function($req, $res) {
  $res->send("hi there from base.");
});

$app->get('/', function($req, $res) {
  $res->send("hi there from root.");
  $res->send($req->get('X-Custom-HEAder'));
});

$app->get('/:number', $auth, function($req, $res) {
  $res->send("You entered " . $req->params['number']);
});




$app->start();


function debug($body) {
  print_r($body);
  echo "<br>\n";
}
