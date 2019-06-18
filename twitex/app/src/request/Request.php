<?php

namespace app\src\request;

class Request
{
    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";

    /**
     * @var array
     */
    private $parameters;

    /**
     * Request constructor
     *
     * @param array $query Query string from the request
     * @param array $request Request body from the request (post method)
     */
    public function __construct(array $query = [], array $request = [])
    {
        $this->parameters = array_merge($query, $request);
    }

    /**
     * Create an instance from global variable
     * this method needs to stay static and have the name create from globals
     *
     * @return Request
     */
    public static function createFromGlobals(){
        return new self($_GET, $_POST);
    }

    /**
     * Return parameter from get or post arguments
     *
     * @param string $name Name of the parameter to retrieve
     * @return mixed
     */
    public function getParameters($name){
        return $this->parameters[$name];
    }

    /**
     * Return the request method used
     * if no method available return get by default
     *
     * @return string
     */
    public function getMethod(){
        return $_SERVER['REQUEST_METHOD'] ?? self::GET;
    }

    /**
     * Return the request URI
     * also takes care of removing the query string to not interfere with our routing system
     *
     * @return string
     */
    public function getUri(){
        $uri = substr($_SERVER['REQUEST_URI'], 7) ?? '/';

        if($pos = strpos($uri, '?')){
            $uri = substr($uri, 0, $pos);
        }

        return $uri;
    }
}