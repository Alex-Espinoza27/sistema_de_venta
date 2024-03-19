<?php
class Controller{
    public function __construct()  {
        $this->views = new Views();
        $this->cargarModel();
    }
    public function cargarModel() {
        #guardamos el nombre de esta clase
        $model = get_class($this)."Model";
        $ruta = "Models/".$model.".php";
        if (file_exists($ruta)) {
            require_once $ruta;
            $this->model = new $model();
        }
    }
}
?>


