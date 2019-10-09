<?php
$documentRouter = $app->router();

$documentRouter->get('/', function($req, $res) {
  $res->send("Hello from DocumentRouter");
});

$documentRouter->get('/today', function($req, $res) {
  $res->send("Hello from DocumentRouter today");
});

$documentRouter->get('/:year', function($req, $res) {
  $res->send("Showing Documents from year: " . $req->params['year']);
});
