<?php

namespace controller;

use app\src\App;

abstract class ControllerBase
{
    protected $app;

    public function __construct(App $app){
        if (!isset ($_SESSION) && $template != 'inscription') $template = 'connexion';
        $this->app = $app;
    }

    protected function render(String $template, Array $params = []){
        ob_start();
        include __DIR__ . '/../view/' . $template . '.php';
        $content = ob_get_contents();
        ob_end_clean(); //Does not send the content of the buffer to the user

        if($template === '404')
        {
            $response = new Response($content, 404, ["HTTP/1.0 404 Not Found"]);
            return $response;
        }

        return $content;
    }
}