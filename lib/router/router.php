<?php

/**
 *
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
   *
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

  public function post(...$args) {
    $this->registerRouteHandler('POST', ...$args);
  }

  public function get(...$args) {
    $this->registerRouteHandler('GET', ...$args);
  }

  public function put(...$args) {
    $this->registerRouteHandler('PUT', ...$args);
  }

  public function delete(...$args) {
    $this->registerRouteHandler('DELETE', ...$args);
  }

  public function all(...$args) {
    $this->registerRouteHandler('ALL', ...$args);
  }

  /**
   *
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
   *
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
   *
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
   * @param string The resource direction for a particular route.
   * @return array Mapping of route parameter names to their values.
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
   *
   */

  protected function removeTrailingSlash($url) {
    if ($url !== '/') {
      return preg_replace("/\/$/", "", $url);
    }

    return $url;
  }
}
