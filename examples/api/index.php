<?php

require_once (dirname(__FILE__) . "/../../lib/rhino.php");

$app = rhino();

$app->use(jsonparse);

$app->get('/', function($req, $res) {
    $res->send("Hello");
});

$app->start();
