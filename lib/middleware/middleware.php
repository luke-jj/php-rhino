<?php

/**
 * Instances of this class are assigned to a router's route queue. A middleware
 * object contains the resource direction or $route, the http $method for that
 * route and a $closure generated from the callback function that was supplied
 * to a routing function such as `.get()` or `.use()` by the programmer.
 */

class Middleware {
  public $route;
  public $method;
  public $closure;
}
