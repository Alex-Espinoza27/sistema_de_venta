<?php

class Productos extends Controller{

    public function __construct() { 
        session_start();
        parent::__construct();
        
    }
    public function index()  {
        if (empty($_SESSION['activo'])) {
            header("location: ".base_url);
        }
        // TODO ESTO ES PARA MOSTRAR SOLO LAS VISTAS HABILITADAS
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user,'productos');
        if (!empty($verificar)  || $id_user == 1 ) { 
            // si en caso tiene permiso entonces mostramos todo
            $data['medidas'] = $this->model->getMedidas();
            $data['categorias'] = $this->model->getCategorias();
            $this->views->getView($this,"index",$data);
        } else {
            header('location:'.base_url.'Errors/Permisos');
        }
    }

    public function listar() {
        $data = $this->model->getProductos();
        #vamos a mostrar los botones de edita4
        for ($i=0; $i < count($data); $i++) { 
            $data[$i]['imagen'] = '<img class="img-thumbnail" src="'.base_url."Assets/img/".$data[$i]['foto'].'" width = "100px" >';
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge bg-success">Activo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-primary" type="button" onclick="btnEditarPro('.$data[$i]['id'].');"><i class = "fas fa-edit"></i></button>
                <button class="btn btn-danger" type="button" onclick="btnEliminarPro('.$data[$i]['id'].')"><i class = "fas fa-trash-alt"></i></button> 
                </div>';
            }
            else{
                $data[$i]['estado'] = '<span class="badge bg-danger">Inactivo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-success" type="button" onclick="btnReingresarPro('.$data[$i]['id'].')"><i class = "fas fa-heart"></i></button>
                </div>';
            }
        }
        echo json_encode($data , JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar() {
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $precio_compra = $_POST['precio_compra'];
        $precio_venta = $_POST['precio_venta'];
        $medida= $_POST['medida'];
        $categoria= $_POST['categoria'];
        $id = $_POST['id'];#guardamso el id para modificarlo

        $img = $_FILES['imagen'];
        $name = $img['name'];// campo especial de img
        $tmpname = $img['tmp_name']; 
        $fecha = date("YmdHis");

        if (empty($codigo) || empty($nombre)  || empty($precio_compra) || empty($precio_venta)) {
            $msg = array('msg'=>"todos los campos son obligatorios",'icono'=>"error"); 
        }
        else{
            // si esque el nombre no esta vacio 
            if (!empty($name)) {
                $imgNombre = $fecha.".jpg";
                $destino = "Assets/img/".$imgNombre;
            }
            else if(!empty($_POST['foto_actual']) && empty($name)){
                $imgNombre = $_POST['foto_actual'];
            }
            //  siesque no eligimos ninguna imagen
            else{ 
                $imgNombre = "default.jpg";
            }

            #si es que esta vacio es porque va a REGISRAR
            if ($id == ""){
                    $data = $this->model->registrarProducto($codigo, $nombre, $precio_compra, $precio_venta,$medida,$categoria, $imgNombre);
                    if ($data == "ok") {
                        // siesque el nombre no esta vacio ingresa
                        if (!empty($name)) {
                            //guardamos la foto en el destino
                            move_uploaded_file($tmpname,$destino);
                        } 
                        $msg = array('msg'=>"El producto se registro con éxito",'icono'=>"success"); 
                    }
                    elseif ($data == "existe") {
                        $msg = array('msg'=>"El nombre ya existe del producto",'icono'=>"warning");  
                    }
                    else{
                        $msg = array('msg'=>"Error al registrar el producto",'icono'=>"error"); 
                    }
            }
            else{
                # el usuario elimino la foto cuando lo edito
                #PROCEDE A MODIFICAR
                $imgDelete = $this->model->editarPro($id);
                if ($imgDelete['foto'] != 'default.jpg') {
                    // bucamos en los archivos si esque existe ya la imagen
                    if (file_exists("Assets/img/" . $imgDelete['foto'])) {
                        // unlink es para eliminar un archivo en el sistema
                        unlink( "Assets/img/" . $imgDelete['foto']);
                    }
                }
                $resultado = $this->model->modificarProducto($codigo, $nombre, $precio_compra, $precio_venta,$medida,$categoria,$imgNombre, $id);
                if ($resultado == "modificado") {
                    if (!empty($name)) {
                        //guardamos la foto en el destino
                        move_uploaded_file($tmpname,$destino);
                    }
                    $msg = array('msg'=>"El producto se modifico con éxito",'icono'=>"success"); 
                }
                else{
                    $msg = array('msg'=>"Error al modificar el producto",'icono'=>"error"); 
                }
            }
        }
        echo json_encode($msg , JSON_UNESCAPED_UNICODE);
        die();
    }
    public function editar(int $id) {
        $data = $this->model->editarPro($id);
        #de esta menera estamos mandando los datos del Producto
        #tambien asi estamos mandando todo a las funciones en javascript
        echo json_encode($data , JSON_UNESCAPED_UNICODE);
        die();
    }
    public function eliminar(int $id) {
        $data = $this->model->accionPro($id,0);
        if($data == 1){
            $msg = array('msg'=>"El producto se elimino con éxito",'icono'=>"success"); 
        }
        else{
            $msg = array('msg'=>"Error al eliminar el producto",'icono'=>"error"); 
        }
        echo json_encode($msg , JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar(int $id) {
        $data = $this->model->accionPro($id,1);
        if($data == 1){
            $msg = array('msg'=>"El producto se reingresar con éxito",'icono'=>"success"); 
        }
        else{
            $msg = array('msg'=>"Error al reingresar el producto",'icono'=>"error"); 
        }
        echo json_encode($msg , JSON_UNESCAPED_UNICODE);
        die();
    }
}
?>
