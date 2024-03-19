<?php
    require_once "Config/config.php";
    
    #guarda la ruta url actual: despues del nombre del proyecto actual 
    $ruta = !empty($_GET['url']) ? $_GET['url'] : "Home/index";
    #lo separamos a un array
    $array = explode("/", $ruta); 

    #asiganamos al controller el url controlador
    $controller = $array[0];
    $metodo = "index";
    $parametro = "";

    #controlador/metodo/parametro
    #si existe el metodo
    if (!empty($array[1])) {
        if (!empty($array[1] != "")) {
            $metodo = $array[1];
        }
    }
    #si existe el parametro
    if (!empty($array[2])) {
        if (!empty($array[2] != "")) {
            #concatenamos todo el paramentro en uno solo
            for ($i = 2; $i < count($array); $i++) {
                #concatenamos
                $parametro .= $array[$i] . ",";
            }
            #quitamos la ultima coma
            $parametro = trim($parametro, ",");
        }
    }
    
    require_once 'Config/App/Autoload.php';
    #   require_once 'Config/Helpers.php';

    #crea un nuevo directorio: para amancenar la ruta de nuestra carpeta controler
    $dirControllers = "Controllers/" . $controller . ".php";

    #existe el direcotrio?
    if (file_exists($dirControllers)) {
        #vamos a requerirlo
        require_once $dirControllers;
        $controller = new $controller();
        if (method_exists($controller, $metodo)) {
            #llamamos el metodo existente dentro del controller
            $controller->$metodo($parametro);
        } 
        else {
            echo "no existe el metodo";
            header('Location: ' . base_url . 'errors');
        }
    }
    else {
        // echo "no existe el controlador";
        header('Location: ' . base_url . 'errors');
    }

?>
