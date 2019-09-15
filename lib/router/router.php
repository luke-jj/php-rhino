<?php

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

  public function use(...$args) {
    $route = '';

    if (is_string($args[0])) {
      $route = array_shift($args);
    } else {
      $route = '/*';
    }

    if ($args[0] instanceof Router) {
      ($args[0])->mountpath = $route;
      ($args[0])->route = $route . "*";
      $this->queue[] = $args[0];

      return;
    }

    foreach ($args as $arg) {
      $middleware = new Middleware();
      $middleware->method = 'ALL';
      $middleware->route = $route;
      $middleware->closure = function($mountpath, $req, $res) use ($arg, $route) {
        $req->params = Router::extractRouteParameters($route, $req->url);

        // execute callback function
        $arg($req, $res);
      };

      $this->queue[] = $middleware;
    }
  }

  public function post(...$args) {
    $route = '';

    if (is_string($args[0])) {
      $route = array_shift($args);
    } else {
      $route = '/*';
    }

    foreach ($args as $arg) {
      $middleware = new Middleware();
      $middleware->method = 'POST';
      $middleware->route = $route;
      $middleware->closure = function($mountpath, $req, $res) use ($arg, $route) {
        $req->params = Router::extractRouteParameters($route, $req->url);

        // execute callback function
        $arg($req, $res);

        $res->end();
      };

      $this->queue[] = $middleware;
    }
  }

  public function get(...$args) {
    $route = '';

    if (is_string($args[0])) {
      $route = array_shift($args);
    } else {
      $route = '/*';
    }

    foreach ($args as $arg) {
      $middleware = new Middleware();
      $middleware->method = 'GET';
      $middleware->route = $route;
      $middleware->closure = function($mountpath, $req, $res) use ($arg, $route) {
        $req->params = Router::extractRouteParameters($route, $req->url);

        // execute callback function
        $arg($req, $res);

        $res->end();
      };

      $this->queue[] = $middleware;
    }
  }

  public function put(...$args) {
    $route = '';

    if (is_string($args[0])) {
      $route = array_shift($args);
    } else {
      $route = '/*';
    }

    foreach ($args as $arg) {
      $middleware = new Middleware();
      $middleware->method = 'PUT';
      $middleware->route = $route;
      $middleware->closure = function($mountpath, $req, $res) use ($arg, $route) {
        $req->params = Router::extractRouteParameters($route, $req->url);

        // execute callback function
        $arg($req, $res);

        $res->end();
      };

      $this->queue[] = $middleware;
    }
  }

  public function delete(...$args) {
    $route = '';

    if (is_string($args[0])) {
      $route = array_shift($args);
    } else {
      $route = '/*';
    }

    foreach ($args as $arg) {
      $middleware = new Middleware();
      $middleware->method = 'DELETE';
      $middleware->route = $route;
      $middleware->closure = function($mountpath, $req, $res) use ($arg, $route) {
        $req->params = Router::extractRouteParameters($route, $req->url);

        // execute callback function
        $arg($req, $res);

        $res->end();
      };

      $this->queue[] = $middleware;
    }
  }

  public function all(...$args) {
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

  public function registerRouter($route, $router) {
  }

  protected function removeTrailingSlash($url) {
    if ($url !== '/') {
      return preg_replace("/\/$/", "", $url);
    }

    return $url;
  }
}
