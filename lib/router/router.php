<?php

/*
 * php-rhino micro-framework
 * Copyright (c) 2019 Luca J
 * Licensed under the MIT license.
 */

/**
 * The Router serves as a collection to which route handlers, middleware and
 * other routers can be registered to.
 *
 * The objects registered to this router are stored in the $queue collection.
 *
 * This collection is traversed by the main collection until all matching
 * routes have been found or the request response cycle has been terminated.
 *
 * Numerous functions to register routes to this router are available as
 * instance methods on this class.
 *
 * Use the .use(), .get(), .post(), .put(), .delete(), and .all() methods
 * to register routes with a router. See the api documentation for examples.
 */

class Router {

  public $req;
  public $res;
  public $mountpath = '';            // set with app.use('/users', users)
  public $route = '';                // automatically set
  public $queue = array();

  public function __construct($req, $res) {
      $this->req = $req;
      $this->res = $res;
  }

  /**
   * Register the provided arguments with this router's routing $queue either
   * as middleware or as a router according to their data type.
   *
   * Optionally a url string may be provided to specify the target route or
   * resource direction. If no url string is provided the root location will
   * be assumed as the default route.
   *
   * @param $args Array - Array of a url string, closure functions or Router.
   */

  public function use(...$args) {
    $route = '';
    $router = null;

    if (is_string($args[0])) {
      $route = array_shift($args);
    } else {
      $route = '/*';
    }

    foreach ($args as $arg) {
      if ($arg instanceof Router) {
        $arg->mountpath = $route;
        $route .= '*';
        $arg->route = $route;
        $router = $arg;

        $index = array_search($router, $args);
        array_splice($args, $index, 1);

        break;
      }
    }

    foreach ($args as $arg) {
      $this->registerMiddleware($route, $arg);
    }

    $this->registerRouter($router);
  }

  /**
   * Register provided arguments as middleware by calling the
   * `registerRouteHandler()` function with the appropriate http method.
   *
   * @param $args Array - Array of a url string and closure functions.
   */

  public function post(...$args) {
    $this->registerRouteHandler('POST', ...$args);
  }

  /**
   * Register provided arguments as middleware by calling the
   * `registerRouteHandler()` function with the appropriate http method.
   *
   * @param $args Array - Array of a url string and closure functions.
   */

  public function get(...$args) {
    $this->registerRouteHandler('GET', ...$args);
  }

  /**
   * Register provided arguments as middleware by calling the
   * `registerRouteHandler()` function with the appropriate http method.
   *
   * @param $args Array - Array of a url string and closure functions.
   */

  public function put(...$args) {
    $this->registerRouteHandler('PUT', ...$args);
  }

  /**
   * Register provided arguments as middleware by calling the
   * `registerRouteHandler()` function with the appropriate http method.
   *
   * @param $args Array - Array of a url string and closure functions.
   */

  public function delete(...$args) {
    $this->registerRouteHandler('DELETE', ...$args);
  }

  /**
   * Register provided arguments as middleware by calling the
   * `registerRouteHandler()` function with the appropriate http method.
   *
   * @param $args Array - Array of a url string and closure functions.
   */

  public function all(...$args) {
    $this->registerRouteHandler('ALL', ...$args);
  }

  /**
   * Register all closure functions provided as arguments as middlware objects
   * on this router's routing $queue.
   *
   * The vararg parameter may optionally contain a url string - route - as
   * it's first parameter. If no url string is provided then the root path
   * of this router will be used as the registration route.
   *
   * The provided http request method specifies for which types of http request
   * the closures should be executed.
   *
   * @param $method string - http request method
   * @param $args Array - Array of an optional url string and closure functions.
   */

  private function registerRouteHandler($method, ...$args) {
    $route = '';

    if (is_string($args[0])) {
      $route = array_shift($args);
    } else {
      $route = '/*';
    }

    $routeHandler = array_pop($args);

    foreach($args as $arg) {
      $this->use($route, $arg);
    }

    $middleware = new Middleware();
    $middleware->method = $method;
    $middleware->route = $route;
    $middleware->closure = function($mountpath, $req, $res) use ($routeHandler, $route) {
      $req->params = Router::extractRouteParameters($mountpath . $route, $req->url);

      // execute callback function
      $routeHandler($req, $res);

      $res->end();
    };

    $this->queue[] = $middleware;
  }

  /**
   * Create a middleware object from a given $route and $callback, and add this
   * middleware object to this router's routing $queue.
   *
   * @param $route string - The resource direction, or url for a middleware.
   * @param $callback Function - handler to be executed if routes match.
   */

  private function registerMiddleware($route, $callback) {
    $middleware = new Middleware();
    $middleware->method = 'ALL';
    $middleware->route = $route;
    $middleware->closure = function($mountpath, $req, $res) use ($route, $callback) {
      $req->params = Router::extractRouteParameters($mountpath . $route, $req->url);

      // execute callback function
      $callback($req, $res);
    };

    $this->queue[] = $middleware;
  }

  /**
   * Add the given router to this router's routing $queue.
   *
   * @param $router Router
   */

  private function registerRouter($router) {

    if ($router !== null) {
      $this->queue[] = $router;
    }
  }

  /**
   * This function is used by the Application to run the http request
   * against all registered routes. Registered routes are stored in the
   * $queue collection.
   *
   * @param $router Router
   */

  protected function executeRouter(Router $router) {

    foreach ($router->queue as $middleware) {

      // if $middleware is router call this function recursively.
      if ($middleware instanceof Router) {

        if ($this->matchUrl($middleware->route, $router->req->url)) {
          $router->executeRouter($middleware);
        }

        continue;
      }

      // match http method
      if (!($middleware->method === 'ALL' || $middleware->method === $router->req->method)) {
        continue;
      }

      // match route
      if (!$this->matchUrl($router->mountpath . $middleware->route, $router->req->url)) {
        continue;
      }

      ($middleware->closure) ($router->mountpath, $router->req, $router->res);
    }
  }

  /**
   * Http request resource location must match the '$resourceDirection`
   * parameter.
   *
   * @param $resourceDirection string - resource direction
   * @return bool
   */

  private function matchUrl($route, $url) {
    $route = $this->removeTrailingSlash($route);
    $url = $this->removeTrailingSlash($url);

    if ($this->hasRouteParameters($route)) {
      $route = preg_replace( "/:[0-9A-Za-z]*/", "[0-9A-Za-z]*", $route);
    } else {
      $route = preg_replace("/\*/", ".*", $route);
    }

    $route = preg_replace("/\//", "\/", $route);
    $route = "/^" . $route . "$/";

    if (preg_match($route, $url)) {
      return true;
    }

    return false;
  }

  /**
   * Return true if this resource direction has user defined parameter(s)
   *
   * @param string The resource direction for a particular route.
   * @return bool
   */

  private function hasRouteParameters($route) {

    if (preg_match("/\/:/", $route)) {
      return true;
    }

    return false;
  }

  /**
   * Extract route parameters defined with a colon `:` in the resource
   * direction given to a specific route.
   *
   * @param $route string - The resource direction for a particular route.
   * @param $url string - The concrete resource location.
   * @return Array Mapping of route parameter names to their values.
   */

  public static function extractRouteParameters($route, $url) {

    if (preg_match("/:/", $route)) {

      $params = array();

      $routeParameterNames = preg_split("/\/:/", $route);
      $resourceBaseLocation = array_shift($routeParameterNames);

      $bareParameters = str_replace($resourceBaseLocation."/", "", $url);

      $parameterValues = preg_split("/\//", $bareParameters);

      foreach($routeParameterNames as $parameterName) {
        $params[$parameterName] = array_shift($parameterValues);
      }

      return $params;
    }
  }

  /**
   * Remove a trailing forward slash from a url.
   *
   * @param $url string - a resource location url string
   * @return string - stripped url string
   */

  protected function removeTrailingSlash($url) {

    if ($url !== '/') {
      return preg_replace("/\/$/", "", $url);
    }

    return $url;
  }
}
