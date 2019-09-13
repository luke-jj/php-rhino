<?php

function jsonparse($req, $res) {
  $body = file_get_contents('php://input'),

  if (!empty($body)) {
    $req->body = json_decode($body, true);
  } else {
    $req->body = array();
  }
}
