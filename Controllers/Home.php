<?php
class Home  extends  Controller {
    public function __construct() {
        #iniciamos la sesion para utiist los _SESSIION
        session_start();

        #siesque existe alguna sesion activa
        if (!empty($_SESSION['activo'])) {
            #no hay ninga sesion iniciada o activa
            header("location: ".base_url."Usuarios");
        }
        parent::__construct();
    }
    public function index()
    {
        #$data['title'] = 'Pagina Principal';
        // $data['nombre'] = 1;
        $this->views->getView($this, "index");
    }
}
?>