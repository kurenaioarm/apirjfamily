<?php

class Bootstrap {

    function __construct() {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = explode('/', $url);

        //print_r($varUrl);

        if (empty($url[0])) {
            require 'controllers/index.php';
            //load controller
            $controller = new Index();
            //load model
            $controller->loadModel('index');
            //call method in controller
            $controller->index();
            return FALSE;
        }

        $file = 'controllers/' . $url[0] . '.php';
        if (file_exists($file)) {
            require $file;
        } else {
            $this->error();
            exit();
        }

        $controller = new $url[0];
        $controller->loadModel($url[0]);

        //calling methods
        if (isset($url[2])) {
            if (method_exists($controller, $url[1])) {
                $controller->{$url[1]}($url[2]);
            } else {
                $this->error();
            }
        } else {
            if (isset($url[1])) {
                if (method_exists($controller, $url[1])) {
                    $controller->{$url[1]}();
                } else {
                    $this->error();
                }
            } else {
                $controller->index();
            }
        }
    }

    function error() {
        $file = 'controllers/error.php';
        if (file_exists($file)) {
            require $file;
            $controller = new Error();
            $controller->index();
            return FALSE;
        } else {
            echo 'error file not found';
            return FALSE;
        }
        return FALSE;
    }

}
