<?php

spl_autoload_register(function($class){
    #esite el archivo que esta en la carpeta ?
    if (file_exists("Config/App/".$class.".php")) {
        require_once "Config/App/" . $class . ".php";
    }
})


?>