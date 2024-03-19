<?php

class Usuarios extends Controller
{

    public function __construct()
    {
        #iniciamos la sesion para utiist los _SESSIION
        session_start();
        #inicializamos la instancia de la clase padre
        parent::__construct();
    }
    public function index()
    {
        if (empty ($_SESSION['activo'])) {
            #no hay ninguna sesion iniciada o activa
            header("location: " . base_url);
        }
        // TODO ESTO ES PARA MOSTRAR SOLO LAS VISTAS HABILITADAS PARA EL CLIENTE
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user, 'usuarios');
        if (!empty ($verificar) || $id_user == 1) {
            $data['cajas'] = $this->model->getCajas();
            $this->views->getView($this, "index", $data);
        } else {
            header('location:' . base_url . 'Errors/Permisos');
        }
    }
    
    #Muestra todos lo registros de los nuevos usuarios
    public function listar()
    {
        $data = $this->model->getUsuarios();
        #vamos a mostrar los botones de edita4
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge bg-success">Activo</span>';
                if ($data[$i]['id'] == 1) {
                    $data[$i]['acciones'] = '<div>
                    <span class="badge bg-primary">Administrador</span>';
                    '</div>';
                } else {
                    $data[$i]['acciones'] = '<div>
                    <a class="btn btn-dark" href="' . base_url . 'Usuarios/permisos/' . $data[$i]['id'] . '" ><i class = "fas fa-key"></i></a>
                    <button class="btn btn-primary" type="button" onclick="btnEditarUser(' . $data[$i]['id'] . ');"><i class = "fas fa-edit"></i></button>
                    <button class="btn btn-danger" type="button" onclick="btnEliminarUser(' . $data[$i]['id'] . ')"><i class = "fas fa-trash-alt"></i></button> 
                    </div>';
                }
            } else {
                $data[$i]['estado'] = '<span class="badge bg-danger">Inactivo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-success" type="button" onclick="btnReingresarUser(' . $data[$i]['id'] . ')"><i class = "fas fa-heart"></i></button>
                </div>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    // Esto es para iniciar sesion
    public function validar()
    {
        if (empty ($_POST['usuario']) || empty ($_POST['clave'])) {
            $msg = "Los campos estan vacios";
        } else {
            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];
            // vamos a encriptar para  verificar la contraseña
            $hash = hash("SHA256", $clave);
            $data = $this->model->getUsuario($usuario, $hash);
            if ($data) {
                #si esque existe algo en data, _SESSION almacena el id aun cuando entre a otras pantallas
                #pra
                $_SESSION['id_usuario'] = $data['id'];
                $_SESSION['usuario'] = $data['usuario'];
                $_SESSION['nombre'] = $data['nombre'];
                #esto es para privar el url, y sera para ver siesque a inicado sesion o no 
                $_SESSION['activo'] = true;
                $msg = "ok";
            } else {
                $msg = "Usuario o contraseña incorrecta";
            }
        }
        #json_encode: convierte un array a formato json
        # JSON_UNES... es para que acepte letras especiales
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        #cortamos las peticiones
        die();
    }
    public function registrar()
    {
        $usuario = $_POST['usuario'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $clave = $_POST['clave'];
        $confirmar = $_POST['confirmar'];
        $caja = $_POST['caja'];#aqui se va guardar el id porque asi lo configuramos
        $id = $_POST['id'];#guardamso el id para modificarlo

        // encriptamos la contraseña para mayor seguridad
        $hash = hash("SHA256", $clave);
        // en la db se guarda asi : 58bb119c35513a451d24dc20ef0e9031ec85b35bfc919d263e..

        if (empty ($usuario) || empty ($nombre) || empty ($caja) || empty ($correo)) {
            $msg = array('msg' => "Todos los campos son obligatorios", 'icono' => "warning");
        } else {
            #si es que esta vacio es porque va a REGISRAR
            if ($id == "") {
                #PROCEDE A REGISTRAR
                if ($clave != $confirmar) {
                    $msg = array('msg' => "las contraseñas no coinciden", 'icono' => "warning");
                } else {
                    $data = $this->model->registrarUsuario($usuario, $nombre, $correo, $hash, $caja);
                    if ($data == "ok") {
                        $msg = array('msg' => "Usuario registrado con exito", 'icono' => "success");
                    } elseif ($data == "existe") {
                        $msg = array('msg' => "El usuario ya existe", 'icono' => "warning");
                    } else {
                        $msg = array('msg' => "Error al registrar el usuario", 'icono' => "error");
                    }
                }
            } else {
                #PROCEDE A MODIFICAR
                $resultado = $this->model->modificarUsuario($usuario, $nombre, $correo, $caja, $id);
                if ($resultado == "modificado") {
                    $msg = array('msg' => "Usuario modificado con exito", 'icono' => "success");
                } else {
                    $msg = array('msg' => "Error al modificar el usuario ", 'icono' => "error");
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    #Esto es para obtener los datos del usuario a editar
    public function editar(int $id)
    {
        $data = $this->model->editarUser($id);
        #de esta menera estamos mandando los datos del usuario
        #tambien asi estamos mandando todo a las funciones en javascript
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function eliminar(int $id)
    {

        $data = $this->model->accionUser($id, 0);// si esque resulta bien, devuelve un 1
        if ($data == 1) {
            $msg = array('msg' => "Usuario eliminado con exito", 'icono' => "success");
        } else {
            $msg = array('msg' => "Error al eliminar el usuario", 'icono' => "error");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar(int $id)
    {
        $data = $this->model->accionUser($id, 1);
        if ($data == 1) {
            $msg = array('msg' => "Usuario reingresado con exito", 'icono' => "success");
        } else {
            $msg = array('msg' => "Error al reingresar el usuario", 'icono' => "error");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function cambiarPass()
    {
        $actual = $_POST['clave_actual'];
        $nueva = $_POST['clave_nueva'];
        $confirmar = $_POST['confirmar_clave'];

        if (empty ($actual) || empty ($nueva) || empty ($confirmar)) {
            $mensaje = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($nueva != $confirmar) {
                $mensaje = array('msg' => 'Las contraseñas no coinciden', 'icono' => 'warning');
            } else {
                $id = $_SESSION['id_usuario'];
                $hash = hash("SHA256", $actual);
                $data = $this->model->getPass($hash, $id);
                if (!empty ($data)) {
                    $verificar = $this->model->modificarPass(hash("SHA256", $nueva), $id);
                    if ($verificar == 1) {
                        $mensaje = array('msg' => 'La contraseña fue modificado con EXITO', 'icono' => 'success');
                    } else {
                        $mensaje = array('msg' => 'Error al modificar la contraseña', 'icono' => 'error');
                    }
                } else {
                    $mensaje = array('msg' => 'La contraseña actual incorrecta', 'icono' => 'warning');
                }
            }
            echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    // -------------------  TODO PERMISOS-----------------------------
    public function Permisos($id)
    {
        if (empty ($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        $data['datos'] = $this->model->getPermisos();
        $permisos = $this->model->getDetallePermisos($id);
        $data['asignados'] = array();
        foreach ($permisos as $permiso) {
            // si existe en e arreglo le asignamos un true
            $data['asignados'][$permiso['id_permiso']] = true;
        }
        $data['id_usuario'] = $id;
        $this->views->getView($this, "permisos", $data);
    }
    public function registrarPermisos()
    {
        $msg = '';
        $id_user = $_POST['id_usuario'];
        $eliminar = $this->model->eliminarPermisos($id_user);
        if ($eliminar == "ok") {
            foreach ($_POST['permisos'] as $id_permiso) {
                $msg = $this->model->registrarPermisos($id_user, $id_permiso);
            }
            if ($msg == "ok") {
                $msg = array('msg' => "Permisos asiganados con éxito", 'icono' => "success");
            } else {
                $msg = array('msg' => "Error al asignar los permisos ", 'icono' => "error");
            }
        } else {
            $msg = array('msg' => "Error al eliminar los permisos anteriores", 'icono' => "error");
        }
        // print_r(json_encode($msg));
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    // -------------------------------------------------MOSTRAR INFORMACION DEL USUARIO------------------------
    public function informacion()
    {
        if (empty ($_SESSION['activo'])) {
            #no hay ninguna sesion iniciada o activa
            header("location: " . base_url);
        }
        $id_user = $_SESSION['id_usuario'];
        $data['datos'] = $this->model->editarUser($id_user); 
        $data['caja_usuario'] = $this->model->getCajasNombre($id_user);
        $this->views->getView($this, "informacion", $data);
    }
    public function actualizarDatos()
    {
        $usuario = $_POST['usuario'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $caja = $_POST['caja'];#aqui se va guardar el id porque asi lo configuramos
        $id = $_POST['id'];#guardamso el id para modificarlo

        $resultado = $this->model->modificarUsuario($usuario, $nombre, $correo, $caja, $id);
        if ($resultado == "modificado") {
            $msg = array('msg' => "Usuario modificado con exito", 'icono' => "success");
        } else {
            $msg = array('msg' => "Error al modificar el usuario ", 'icono' => "error");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function salir()
    {
        //destruimos las sesiones y lo salimos
        session_destroy();
        // lo direccionamos al login
        header("location: " . base_url);
    }
}
?>