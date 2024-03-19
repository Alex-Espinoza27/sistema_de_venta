<?php

class Clientes extends Controller
{
    public function __construct()
    {
        #iniciamos la sesion para utiist los _SESSIION
        session_start();

        if (empty ($_SESSION['activo'])) {
            #no hay ninguna sesion iniciada o activa
            header("location: " . base_url);
        }
        #inicializamos la instancia de la clase padre
        parent::__construct();

    }
    public function index()
    {
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user, 'clientes');
        if (!empty ($verificar) || $id_user == 1) {
            $this->views->getView($this, "index");
        } else {
            header('location:' . base_url . 'Errors/Permisos');
        }
    }
    public function listar()
    {
        $data = $this->model->getClientes();
        #vamos a mostrar los botones de edita4
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge bg-success">Activo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-primary" type="button" onclick="btnEditarCli(' . $data[$i]['id'] . ');"><i class = "fas fa-edit"></i></button>
                <button class="btn btn-danger" type="button" onclick="btnEliminarCli(' . $data[$i]['id'] . ')"><i class = "fas fa-trash-alt"></i></button>
                </div>';
            } else {
                $data[$i]['estado'] = '<span class="badge bg-danger">Inactivo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-success" type="button" onclick="btnReingresarCli(' . $data[$i]['id'] . ')"><i class = "fas fa-heart"></i></button>
                </div>';
            }
            #en la funcion pasamos el id del registro seleccionado

        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user, 'registrar_clientes');
        if (!empty ($verificar) || $id_user == 1) {
            $dni = $_POST['dni'];
            $nombre = $_POST['nombre'];
            $direccion = $_POST['direccion'];
            $telefono = $_POST['telefono'];#aqui se va guardar el id porque asi lo configuramos
            $id = $_POST['id'];#guardamso el id para modificarlo
            if (empty ($dni) || empty ($nombre) || empty ($telefono) || empty ($direccion)) {
                $msg = "todos los campos son obligatorios";
            } else {
                #si es que esta vacio es porque va a REGISRAR
                if ($id == "") {
                    #PROCEDE A REGISTRAR
                    $data = $this->model->registrarCliente($dni, $nombre, $telefono, $direccion);
                    if ($data == "ok") {
                        $msg = array('msg' => "El cliente fue ingresado con exito", 'icono' => "success");
                    } elseif ($data == "existe") {
                        $msg = array('msg' => "El dni ya existe", 'icono' => "warning");
                    } else {
                        $msg = array('msg' => "Error al registrar el cliente", 'icono' => "error");
                    }
                } else {
                    #PROCEDE A MODIFICAR
                    $resultado = $this->model->modificaCliente($dni, $nombre, $telefono, $direccion, $id);
                    if ($resultado == "modificado") {
                        $msg = array('msg' => "El cliente fue modificado con exito", 'icono' => "success");
                    } else {
                        $msg = array('msg' => "Error al modificar el cliente", 'icono' => "error");
                    }
                }
            }
        } else {
            $msg = array('msg' => "Error, usted no tiene permiso pare registrar clientes", 'icono' => "error");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    #Esto es para obtener los datos del dni a editar
    public function editar(int $id)
    {
        $data = $this->model->editarCli($id);
        #de esta menera estamos mandando los datos del dni
        #tambien asi estamos mandando todo a las funciones en javascript
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function eliminar(int $id)
    {
        // TODO ESTO ES PARA MOSTRAR SOLO LAS VISTAS HABILITADAS PARA EL CLIENTE
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user, 'eliminar_clientes');
        if (!empty ($verificar) || $id_user == 1) {
            $data = $this->model->accionCli($id, 0);// si esque resulta bien, devuelve un 1
            if ($data == 1) {
                $msg = array('msg' => "El cliente fue eliminado con exito", 'icono' => "success");
            } else {
                $msg = array('msg' => "Error al eliminar el cliente", 'icono' => "error");
            }
        } else {
            $msg = array('msg' => "Error, usted no tiene permiso para eliminar los clientes", 'icono' => "error");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar(int $id)
    {
        $data = $this->model->accionCli($id, 1);
        if ($data == 1) {
            $msg = array('msg' => "El cliente fue reingresado con éxito", 'icono' => "success");
        } else {
            $msg = array('msg' => "Error al reingresar el cliente", 'icono' => "error");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

}
?>