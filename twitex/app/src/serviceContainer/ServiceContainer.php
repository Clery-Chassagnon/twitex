<?php

namespace app\src\serviceContainer;

class ServiceContainer
{
    /**contains all services of the php app
     *
     * @var array
     */
    private $container = array();


    /**
     * Get a service in the container
     *
     * @param string $serviceName Name of the service to get in the container
     * @return mixed
     */
    public function get(string $serviceName){
        return $this->container[$serviceName];
    }

    /**
     * Create a service in the container
     *
     * @param string $name Name of the service to retrieve
     * @param mixed $assigned Value associated to the service (can be any type
     */
    public function set(string $name, $assigned){
        $this->container[$name] = $assigned;
    }

    /**
     * Unset a service in the container
     *
     * @param string $name Name of the service to unset in the container
     */
    public function unset(string $name){
        unset($this->container[$name]);
    }
}