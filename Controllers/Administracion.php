<?php
class Administracion extends Controller
{
    public function __construct()
    {
        session_start();
        if (empty ($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        parent::__construct();
    }
    public function index()
    {
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user, 'configuracion');
        // verificamos si tiene permiso y que tambien solo se trate del user administrador
        if (!empty ($verificar) || $id_user == 1) {
            $data = $this->model->getEmpresa();
            $this->views->getView($this, "index", $data);
        } else {
            header('location:' . base_url . 'Errors/Permisos');
        }
    }
    // este metodo es llamado en login.js donde se direccionara a esta vista al ingresar
    public function home()
    {
        // TODO ESTO ES PARA MOSTRAR SOLO LAS VISTAS HABILITADAS
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user, 'arqueo_caja');
        if (!empty ($verificar) || $id_user == 1) {
            // este metodo es para mostrar el valor de $data en la vista home
            $data['usuarios'] = $this->model->getDatos('Usuarios');
            $data['clientes'] = $this->model->getDatos('Clientes');
            $data['productos'] = $this->model->getDatos('Productos');
            $data['ventas'] = $this->model->getVentas_y_Compras('ventas');
            $data['categorias'] = $this->model->getDatos('Categorias');
            $data['medidas'] = $this->model->getDatos('Medidas');
            $data['caja'] = $this->model->getDatos('caja');
            $data['compras'] = $this->model->getVentas_y_Compras('compras');
            $this->views->getView($this, "home", $data);
        } else {
            header('location:' . base_url . 'Errors/Permisos');
        }
    }
    public function modificar()
    {
        $nombre = $_POST['nombre'];
        $ruc = $_POST['ruc'];
        $tel = $_POST['telefono'];
        $dir = $_POST['direccion'];
        $mensaje = $_POST['mensaje'];

        $id = $_POST['id'];
        $data = $this->model->modificar($ruc, $nombre, $tel, $dir, $mensaje, $id);
        if ($data == 'ok') {
            $msg = array('msg' => "Datos modificados con éxito", 'icono' => "success");
        } else {
            $msg = array('msg' => "Error al modificar los datos", 'icono' => "error");
        }
        echo json_encode($msg);
        die();
    }

    public function reporteStock()
    {
        $data = $this->model->getStockMinimo();
        echo json_encode($data);
        die();
    }

    public function productosVendidos()
    {
        $data = $this->model->getProductosVendidos();
        echo json_encode($data);
        die();
    }


}

?>