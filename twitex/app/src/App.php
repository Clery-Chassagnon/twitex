<?php

namespace app\src;

use app\src\response\Response;
use app\src\request\Request;
use app\src\route\Route;
use app\src\serviceContainer\ServiceContainer;

class App
{
    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";

    /**
     * @var array
     */
    private $routes = array();

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var serviceContainer
     */
    private $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * Retrieve a service from the service container
     *
     * @param string $serviceName Name of the service to retrieve
     * @return mixed
     */
    public function getService(string $serviceName){
        return $this->serviceContainer->get($serviceName);
    }

    /**
     * Set a service in the service container
     *
     * @param string $serviceName Name of the service to set
     * @param mixed $assigned Value of the service to set
     */
    public function setService(string $serviceName, $assigned){
        $this->serviceContainer->set($serviceName, $assigned);
    }

    /**
     * Creates a route forHTTP verb Get
     *
     * @param string $pattern
     * @param callable $callable
     * @return App $this
     */
    public function get(string $pattern, callable $callable){
        $this->registerRoute(self::GET, $pattern, $callable);

        return $this;
    }

    public function post(string $pattern, callable $callable){
        $this->registerRoute(self::POST, $pattern, $callable);

        return $this;
    }

    public function put(string $pattern, callable $callable){
        $this->modifyRoute(self::PUT, $pattern, $callable);

        return $this;
    }

    public function delete(string $pattern, callable $callable){
        $this->deleteRoute(self::DELETE, $pattern, $callable);

        return $this;
    }



    /**
     * Launch the php app
     *
     * @throws \Exception
     */
    public function run(Request $request = null){
        if($request === null){
            $request = Request::createFromGlobals();
        }
        $method = $request->getMethod();
        $uri = $request->getUri();

        foreach($this->routes as $route){
            if($route->match($method, $uri)){
                return $this->process($route, $request);
            }
        }

        throw new \Exception('No routes available for this uri');
    }

    /**
     * Process route
     *
     * @param Route $route
     * @throws \Exception
     */
    private function process(Route $route, Request $request){
        try {
            $arguments = $route->getArguments();
            array_unshift($arguments, $request);
            $content = call_user_func_array($route->getCallable(), $arguments);

            if($content instanceof Response) {
                $content->send();
                return;
            }

            $response = new Response($content, $this->statusCode ?? 200);
            $response->send();
        } catch (\Exception $e){
            throw $e;
        }
    }


    /**
     * Register a route in the routes array
     *
     * @param string $method
     * @param string $pattern
     * @param callable $callable
     */
    private function registerRoute(string $method, string $pattern, callable $callable){
        $this->routes[] = new Route($method, $pattern, $callable);

    }

    private function modifyRoute(string $method, string $pattern, callable $callable){
        $this->routes[] = new Route($method, $pattern, $callable);

    }

    private function deleteRoute(string $method, string $pattern, callable $callable){
        $this->routes[] = new Route($method, $pattern, $callable);

    }

}