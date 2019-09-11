<?php

require_once "/lib/application.php";

function rhino($settings = null) {
  return new Application($settings);
}
