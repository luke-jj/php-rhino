<?php

require_once "../../lib/rhino.php";

$app = rhino();

$app->get('/', function($req, $res) {
    $res->send("Hello");
});

$app->listen();
