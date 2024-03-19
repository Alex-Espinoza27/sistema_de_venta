<?php

class Medidas extends Controller{
    public function __construct() {
        #iniciamos la sesion para utiist los _SESSIION
        session_start();

        if (empty($_SESSION['activo'])) {
            #no hay ninguna sesion iniciada o activa
            header("location: ".base_url);
        }
        #inicializamos la instancia de la clase padre
        parent::__construct();
    }
    public function index()  {
        
        // TODO ESTO ES PARA MOSTRAR SOLO LAS VISTAS HABILITADAS
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user,'medidas');
        if (!empty($verificar)  || $id_user == 1 ) { 
            $this->views->getView($this,"index");
        } else {
            header('location:'.base_url.'Errors/Permisos');
        }
    }

    public function listar() {
        $data = $this->model->getMedidas();
        #vamos a mostrar los botones de edita4
        for ($i=0; $i < count($data); $i++) { 
            
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge bg-success">Activo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-primary" type="button" onclick="btnEditarMed('.$data[$i]['id'].');"><i class = "fas fa-edit"></i></button>
                <button class="btn btn-danger" type="button" onclick="btnEliminarMed('.$data[$i]['id'].')"><i class = "fas fa-trash-alt"></i></button>
                </div>';
            }
            else{
                $data[$i]['estado'] = '<span class="badge bg-danger">Inactivo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-success" type="button" onclick="btnReingresarMed('.$data[$i]['id'].')"><i class = "fas fa-heart"></i></button>
                </div>';
            }
            #en la funcion pasamos el id del registro seleccionado
            
        }
        echo json_encode($data , JSON_UNESCAPED_UNICODE);
        die();
    }    
    public function registrar() {
        $nombre = $_POST['nombre'];
        $nombre_corto = $_POST['nombre_corto'];
        $id = $_POST['id'];#guardamso el id para modificarlo
        if ( empty($nombre)  || empty($nombre_corto) ) {
            $msg = array('msg'=>"todos los campos son obligatorios",'icono'=>"error"); 
        }
        else{
            #si es que esta vacio es porque va a REGISRAR
            if ($id == "") {
                #PROCEDE A REGISTRAR
                $data = $this->model->registrarMedida($nombre, $nombre_corto);
                if ($data == "ok") {
                    $msg = array('msg'=>"La medida se registro con éxito",'icono'=>"success"); 
                }
                elseif ($data == "existe") {
                    $msg = array('msg'=>"El nombre ya existe de la medida",'icono'=>"warning"); 
                }
                else{
                    $msg = array('msg'=>"Error al registrar la medida",'icono'=>"error"); 
                }
            }
            else{
                #PROCEDE A MODIFICAR
                $resultado = $this->model->modificaMedida($nombre, $nombre_corto, $id);
                if ($resultado == "modificado") {
                    $msg = array('msg'=>"La medida fue modificado con éxito",'icono'=>"success"); 
                }
                else{
                    $msg = array('msg'=>"Error al modificar la medida",'icono'=>"error");  
                } 
            }
        }
        echo json_encode($msg , JSON_UNESCAPED_UNICODE);
        die();
    }
    #Esto es para obtener los datos del dni a editar
    public function editar(int $id) {
        $data = $this->model->editarMed($id);
        #de esta menera estamos mandando los datos del dni
        #tambien asi estamos mandando todo a las funciones en javascript
        echo json_encode($data , JSON_UNESCAPED_UNICODE);
        die();
    }
    public function eliminar(int $id) {

        $data = $this->model->accionMed($id,0);// si esque resulta bien, devuelve un 1
        if($data == 1){
            $msg = array('msg'=>"La medida fue eliminado con éxito",'icono'=>"success");  
        }
        else{
            $msg = array('msg'=>"Error al eliminar la medida",'icono'=>"error");  
        }
        echo json_encode($msg , JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar(int $id) {
        $data = $this->model->accionMed($id,1);
        if($data == 1){
            $msg = array('msg'=>"La medida fue reingresado con éxito",'icono'=>"success"); 
        }
        else{
            $msg = array('msg'=>"Error al reingresar la medida",'icono'=>"error"); 
        }
        echo json_encode($msg , JSON_UNESCAPED_UNICODE);
        die();
    }

} 
?>
