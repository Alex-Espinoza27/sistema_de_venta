<?php 
class Errors extends Controller{
    public function index(){
        // podemos $this porque el directorio tiene el mismo nombre 
        $this->views->getView($this,'index');
    }

    public function Permisos(){
        // esto es para hacer visualizar que no puede ingresar a esa vista 
        $this->views->getView($this,'permisos');
    }

}
?>