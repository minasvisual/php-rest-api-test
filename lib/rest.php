<?php

class Rest {

    private $requestMethod;
    private $path;
    private $router = [
      'GET' => [],
      'POST' => [],
      'PUT' => [],
      'PATCH' => [],
      'DELETE' => [],
    ];
    private $middleware = [];

    public function __construct()
    {
    }

    public function addRoute($method, $path, $cb){
        $this->router[$method][$path] = $cb;
    }
  
    public function addMiddleware($regex, $methods, $condition){
        $this->middleware[] = ['regex'=> $regex, 'methods' => $methods, 'condition' => $condition];
    }
  
    public function AddRouteGroup($condition, $cb){
        if( call_user_func($condition, []) )
            call_user_func($cb);
        else $this->permissionDenied();
    }
  
    public function processRequest($requestMethod, $path)
    {
        $this->requestMethod = $requestMethod;
        $this->path = $path;
        try{
          $response = $this->matchPath( $this->router[$requestMethod], $path );

          http_response_code( isset($response['statusCode']) ? $response['statusCode'] : 200 );

          if ( isset($response['body']) ) echo $response['body'];
          if ( isset($response['json']) ){
            header('Content-type: application/json');
            echo json_encode($response['json']);
          }
          
        }catch( Exception $e ){
            http_response_code( $e->getCode() ?? 422);
            $response['body'] = json_encode([
                'error' => $e->getMessage() ?? 'Invalid input'
            ]);
            echo $response['body'];
        }
    }
  

    public function matchPath($routes, $reqUrl)
    {
        $exists = false;
        foreach($routes  as  $route => $callback) {
          
            $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($route)) . "$@D";
            $matches = Array();
          
            if( preg_match($pattern, $reqUrl, $matches) ) {
              
                array_shift($matches);
                $req = [ 
                  'route' => $route,
                  'url' => $this->path,
                  'method' => $this->requestMethod,
                  'params' => $matches,
                  'body' => json_decode(file_get_contents('php://input'), true),
                  'query' => @$_GET,
                  'headers' => apache_request_headers()
                ];
               
                foreach( $this->middleware as $midd ){
                  if( preg_match( $midd['regex'] , $route) && in_array($this->requestMethod, $midd['methods']) ) 
                      $req = call_user_func($midd['condition'], $req);
                }
              
                return call_user_func($callback, $req);
                $exists = true;
            }
        }
        if( $exists === false )  return $this->notFoundResponse();
    }
  

    private function notFoundResponse()
    {
        $response['statusCode'] = 404;
        $response['body'] = 'NOT FOUND';
        return $response;
    }
  
    private function permissionDenied()
    {
        http_response_code(401);
        die(json_encode([
            'message' => "Permission Denied"
        ]));
    }
 
}