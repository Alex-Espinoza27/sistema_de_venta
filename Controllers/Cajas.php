<?php

class Cajas extends Controller
{
    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            #no hay ninguna sesion iniciada o activa
            header("location: " . base_url);
        }
        parent::__construct();
    }
    public function index()
    {
        // TODO ESTO ES PARA MOSTRAR SOLO LAS VISTAS HABILITADAS PARA EL CLIENTE
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user,'cajas');
        if (!empty($verificar)  || $id_user == 1 ) { 
            $this->views->getView($this, "index");
        } else {
            header('location:'.base_url.'Errors/Permisos');
        }
    }
    public function listar()
    {
        $data = $this->model->getCajas('caja');
        #vamos a mostrar los botones de edita4
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge bg-success">Activo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-primary" type="button" onclick="btnEditarCaja(' . $data[$i]['id'] . ');"><i class = "fas fa-edit"></i></button>
                <button class="btn btn-danger" type="button" onclick="btnEliminarCaja(' . $data[$i]['id'] . ')"><i class = "fas fa-trash-alt"></i></button>
                </div>';
            } else {
                $data[$i]['estado'] = '<span class="badge bg-danger">Inactivo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-success" type="button" onclick="btnReingresarCaja(' . $data[$i]['id'] . ')"><i class = "fas fa-heart"></i></button>
                </div>';
            }
            #en la funcion pasamos el id del registro seleccionado
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        $nombre = $_POST['nombre'];
        $id = $_POST['id'];#guardamos el id para modificarlo
        if (empty($nombre)) {
            $msg = array('msg' => "todos los campos son obligatorios", 'icono' => "error");
        } else {
            #si es que esta vacio es porque va a REGISTRAR
            if ($id == "") {
                #PROCEDE A REGISTRAR
                $data = $this->model->registrarCaja($nombre);
                if ($data == "ok") {
                    $msg = array('msg' => "La caja se registro con éxito", 'icono' => "success");
                } elseif ($data == "existe") {
                    $msg = array('msg' => "El nombre ya existe de la caja", 'icono' => "warning");
                } else {
                    $msg = array('msg' => "Error al registrar la caja", 'icono' => "error");
                }
            } else {
                #PROCEDE A MODIFICAR
                $resultado = $this->model->modificarCaja($nombre, $id);
                if ($resultado == "modificado") {
                    $msg = array('msg' => "La caja fue modificado con éxito", 'icono' => "success");
                } else {
                    $msg = array('msg' => "Error al modificar la caja", 'icono' => "error");
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    #Esto es para obtener los datos del dni a editar
    public function editar(int $id)
    {
        $data = $this->model->editarCaja($id);
        #de esta menera estamos mandando los datos del dni
        #tambien asi estamos mandando todo a las funciones en javascript
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function eliminar(int $id)
    {
        $data = $this->model->accionCaja($id, 0);// si esque resulta bien, devuelve un 1
        if ($data == 1) {
            $msg = array('msg' => "La caja fue eliminado con éxito", 'icono' => "success");
        } else {
            $msg = array('msg' => "Error al eliminar la caja", 'icono' => "error");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar(int $id)
    {
        $data = $this->model->accionCaja($id, 1);
        if ($data == 1) {
            $msg = array('msg' => "La caja fue reingresado con éxito", 'icono' => "success");
        } else {
            $msg = array('msg' => "Error al reingresar la caja", 'icono' => "error");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    // ----------------------------------------------TODO ARQUEO DE CAJAS ----------------------------------------------------------------------
    public function arqueo()
    {
        // TODO ESTO ES PARA MOSTRAR SOLO LAS VISTAS HABILITADAS  
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user,'arqueo_caja');
        if (!empty($verificar)  || $id_user == 1 ) { 
            $this->views->getView($this, "arqueo");
        } else {
            header('location:'.base_url.'Errors/Permisos');
        }
    }
    public function abrirArqueo()
    {
        $monto_inicial = $_POST['monto_inicial'];
        $fecha_apertura = date('Y-m-d');
        $id_usuario = $_SESSION['id_usuario'];
        $id = $_POST['id'];#guardamos el id para modificarlo
        if (empty($monto_inicial)) {
            $msg = array('msg' => "todos los campos son obligatorios", 'icono' => "error");
        } else {
            if ($id == '') {
                // cuando va abrir la caja con un monto inicial
                $data = $this->model->registrarArqueo($id_usuario, $monto_inicial, $fecha_apertura);
                if ($data == "ok") {
                    $msg = array('msg' => "Caja abierta con exito", 'icono' => "success");
                } elseif ($data == "existe") {
                    $msg = array('msg' => "La caja ya esta abierta", 'icono' => "warning");
                } else {
                    $msg = array('msg' => "Error al abrir la caja", 'icono' => "error");
                }   
            } 
            else {
                // cuando ya va cerrar la caja
                $monto_final= $this->model->getVentas($id_usuario); 
                // esto es para que cierre la caja aunque no haya vendido nada
                if (empty($monto_final['total'])) {
                    $monto_final['total'] = 0.00;
                }
                $total_ventas = $this->model->getTotalVentas($id_usuario); 
                $inicial= $this->model->getMontoInicial($id_usuario);
                $general = $monto_final['total'] + $inicial['monto_inicial']; 
                
                $data = $this->model->actualizarArqueo($monto_final['total'],$fecha_apertura, $total_ventas['total'],$general,$inicial['id'] );
                if ($data == "ok") {
                    // si la caja se cierra entonces actualizamos las ventas
                    $this->model->actualizarApertura($id_usuario);
                    $msg = array('msg' => "Caja cerrada con exito", 'icono' => "success");
                } else {
                    $msg = array('msg' => "Error al cerrar la caja", 'icono' => "error");
                }
            }
        }
        // print_r( $msg );
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function listar_arqueo()
    {
        $data = $this->model->getCajas('cierre_caja');
        #vamos a mostrar los botones de editar, eliminar..
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge bg-success">Abierta</span>';
            } else {
                $data[$i]['estado'] = '<span class="badge bg-danger">Cerrada</span>';
            }
            #en la funcion pasamos el id del registro seleccionado
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function getVentas()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $data['monto_total'] = $this->model->getVentas($id_usuario);
        // esto es para que cierre la caja aunque no haya vendido nada
        if (empty($data['monto_total']['total'])) {
            $data['monto_total']['total'] = 0.00;
        }
        $data['total_ventas'] = $this->model->getTotalVentas($id_usuario);
        $data['inicial'] = $this->model->getMontoInicial($id_usuario);
        $data['monto_general'] = $data['monto_total']['total'] + $data['inicial']['monto_inicial'];
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function verificarEstadoCaja() {
        $id_usuario = $_SESSION['id_usuario'];
        // cuando si esta abierto la caja
        $data = $this->model->getVerificarEstadoCaja($id_usuario);
        if (!empty($data)) {
            $res = "ok";
        }
        else{
            $res = "error";
        }
        // print_r($res);
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

}
?>