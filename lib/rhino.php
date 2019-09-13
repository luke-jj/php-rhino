<?php

require_once (dirname(__FILE__) . "/application.php");

function rhino($settings = null) {
  return new Application($settings);
}
