<?php

class Router {

  public $req;
  public $res;
  public $mountpath;            // set with app.use('/users', users)
  public $queue = array();

  public function __construct($req, $res) {
      $this->req = $req;
      $this->res = $res;
  }

  public function use(...$args) {
    $path = '';

    if (is_string($args[0])) {
      $path = array_shift($args);
    }

    foreach ($args as $arg) {
      $middleware = function($mountpath, $req, $res) use ($arg) {

        $arg($req, $res);
      };

      $this->queue[] = $middleware;
    }
  }

  public function get(...$args) {
    $this->use(...$args);
  }

  /**
   * This function is used by the Application to run the http request
   * against all registered routes. Registered routes are stored in the
   * $queue collection.
   *
   * @param $router Router
   */

  protected function executeMiddleware(Router $router) {

    foreach ($router->queue as $middleware) {

      // if $middleware is router call this function recursively.
      if (!is_callable($middleware)) {
        $router->executeMiddleware($middleware);
      }

      // else execute this $middleware closure
      ($middleware) ($router->mountpath, $router->req, $router->res);
    }
  }



    // public function get($resourceDirection, $handler) {

        // if (!$this->validateHttpMethod('GET')) {
            // return;
        // }

        // if (!$this->validateResourceDirection($resourceDirection)) {
            // return;
        // }

        // $this->req->params = $this->extractRouteParameters($resourceDirection);

        // $handler($this->req, $this->res);

        // throw new RouterExit();
    // }





  /*
   * Old Code
   */



    /**
     * Http request method must match '$methodName'.
     *
     * @param $methodName string - http method name.
     * @return bool
     */

    // private function validateHttpMethod($methodName) {
        // if ($this->req->getHttpMethod() === strtoupper($methodName)) {
            // return true;
        // }

        // return false;
    // }

    /**
     * Http request resource location must match the '$resourceDirection`
     * parameter.
     *
     * @param $resourceDirection string - resource direction
     * @return bool
     */

    // private function validateResourceDirection($resourceDirection) {

        // if ($this->hasRouteParameters($resourceDirection)) {
            // $resourceDirection = preg_replace( "/:[0-9A-Za-z]*/", "[0-9A-Za-z]*", $resourceDirection);
        // } else {
            // $resourceDirection = preg_replace("/\*/", ".*", $resourceDirection);
        // }

        // $resourceDirection = preg_replace("/\//", "\/", $resourceDirection);
        // $resourceDirection = "/^" . $resourceDirection . "$/";

        // if (preg_match($resourceDirection, $this->req->getResourceLocation())) {
            // $this->foundMatch = true;

            // return true;
        // }

        // return false;
    // }

    /**
     * Return true if this resource direction has user defined parameter(s)
     *
     * @param string The resource direction for a particular route.
     * @return bool
     */

    // private function hasRouteParameters($resourceDirection) {
        // if (preg_match("/\/:/", $resourceDirection)) {
            // return true;
        // }

        // return false;
    // }

    /**
     * Extract route parameters defined with a colon `:` in the resource
     * direction given to a specific route.
     *
     * @param string The resource direction for a particular route.
     * @return array Mapping of route parameter names to their values.
     */

    // private function extractRouteParameters($resourceDirection) {

        // if (preg_match("/:/", $resourceDirection)) {
            // $params = array();

            // $routeParameterNames = preg_split("/\/:/", $resourceDirection);
            // $resourceBaseLocation = array_shift($routeParameterNames);

            // $bareParameters = str_replace(
                // $resourceBaseLocation . "/",
                // "",
                // $this->req->getResourceLocation());

            // $parameterValues = preg_split("/\//", $bareParameters);

            // foreach($routeParameterNames as $parameterName) {
                // $params[$parameterName] = array_shift($parameterValues);
            // }

            // return $params;
        // }
    // }

    /**
     * A routing function that handles HTTP GET requests.
     *
     * @param $handler Function - must be of the form function($req, $res) { }
     */

    // public function get($resourceDirection, $handler) {

        // if (!$this->validateHttpMethod('GET')) {
            // return;
        // }

        // if (!$this->validateResourceDirection($resourceDirection)) {
            // return;
        // }

        // $this->req->params = $this->extractRouteParameters($resourceDirection);

        // $handler($this->req, $this->res);

        // throw new RouterExit();
    // }
}
