<?php

/*
 * php-rhino micro-framework
 * Copyright (c) 2019 Luca J
 * Licensed under the MIT license.
 */

/**
 * Use or include this middleware function in your routing to automatically
 * read the http request body as json and convert the request body to a php
 * array. `req->body` will be set to `null` if the request body contains
 * invalid json.
 *
 * @param $res Response - This application's main http response instance object.
 * @param $req Request - This application's main http request instance object.
 */

$jsonparse = function ($req, $res) {
  $body = file_get_contents('php://input');

  if (!empty($body)) {
    $req->body = json_decode($body, true);
  } else {
    $req->body = array();
  }
};
