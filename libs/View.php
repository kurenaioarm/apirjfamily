<?php

class View {

    function __construct() {
        //echo 'This is in construct view<br>';
    }

    public function render($name, $noInclude = FALSE) {
        if ($noInclude === TRUE) {
            require 'views/' . $name . '.php';
        } else {
            if ($noInclude === 'json') {
                require 'views/json_header.php';
                require 'views/' . $name . '.php';
            } else {
                require 'views/header.php';
                require 'views/' . $name . '.php';
                require 'views/footer.php';
            }
        }
    }

}
