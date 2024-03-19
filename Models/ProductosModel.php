<?php


#AQUI SE HACEN TODOS LO METODOS QUE VA UTILIZAR EN EL ARCHIOVO Producto.php
class ProductosModel extends Query{
    private $codigo, $nombre, $precio_compra, $precio_venta, $id_categoria, $id_medida,$estado,$img, $id;
    public function __construct()  
    {
        #para cargar el constructor de la clase padre
        parent::__construct();
    }
    public function getProductos() {
        #el c.id confunde es por eso que solo muestra el mismo id, lo llamamos con id_caja porque en todo asi estamos usandolo
        $sql = "SELECT p.*, m.id as id_medida, m.nombre as medida, c.id as id_categoria, c.nombre as categoria FROM productos p INNER JOIN medidas m ON p.id_medida = m.id INNER JOIN categorias c ON p.id_categoria=c.id";
        $data = $this->selectALL($sql);
        return $data;
    }
    public function getMedidas() {
        $sql = "SELECT * FROM medidas WHERE estado = 1";
        $data = $this->selectALL($sql);
        return $data;
    }
    public function getCategorias() {
        $sql = "SELECT * FROM categorias WHERE estado = 1";
        $data = $this->selectALL($sql);
        return $data;
    }
    public function registrarProducto(string $codigo, string $nombre, string $precio_compra, string $precio_venta, int $id_medida, int $id_categoria, string $img) {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->precio_compra = $precio_compra;
        $this->precio_venta = $precio_venta;
        $this->id_medida = $id_medida;
        $this->id_categoria = $id_categoria;
        $this->img = $img;
        #verificamos si ya existe el Producto
        $verificar = "SELECT * FROM productos WHERE codigo = '$this->codigo'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            #si en caso no existe el Producto lo registramos
            $sql = "INSERT INTO productos(codigo,descripcion,precio_compra,precio_venta,id_medida,id_categoria,foto) VALUES (?,?,?,?,?,?,?)";
            $datos = array($this->codigo,$this->nombre,$this->precio_compra,$this->precio_venta,$this->id_medida,$this->id_categoria,$this->img);
            #utilizamos la funcion de query
            $dato = $this->save($sql, $datos);
            if ($dato == 1) {
                $res = "ok";
            }
            else{
                $res = "error";
            }
        }
        else{
            $res = "existe";
        }
        return $res;
    }
    //Esto es para obtener los datos del Producto a edita
    public function editarPro(int $id) {
        $sql = "SELECT * FROM productos WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
    public function modificarProducto(string $codigo, string $nombre, string $precio_compra, string $precio_venta, int $id_medida, int $id_categoria,string $name_img, int $id) {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->precio_compra = $precio_compra;
        $this->precio_venta = $precio_venta;
        $this->id_medida = $id_medida;
        $this->id_categoria = $id_categoria;
        $this->img = $name_img;
        $this->id = $id;

        $sql = "UPDATE productos SET codigo = ?, descripcion = ?, precio_compra = ?, precio_venta = ?, id_medida = ?, id_categoria = ?, foto = ?  WHERE id = ?";
        $datos = array($this->codigo,$this->nombre,$this->precio_compra,$this->precio_venta,$this->id_medida,$this->id_categoria, $this->img, $this->id);
        $dato = $this->save($sql, $datos);
        if ($dato == 1) {
            $res = "modificado";
        }
        else{
            $res = "error al modificar el Producto";
        }
        return $res;
    }
    // esto es para que sea para eliminar y reingresar
    public function accionPro(int $id, int $estado) {
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE productos SET estado = ? WHERE id = ?";
        $datos = array($this->estado, $this->id);
        $data = $this->save($sql, $datos);
        return $data;
    }
    public function verificarPermiso(int $id_user, string $nombre) {
        $sql = "SELECT  p.id,p.permiso, d.id,d.id_usuario,d.id_permiso FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.permiso = '$nombre'";
        $data = $this->selectALL($sql);
        return $data;
    }
}

?>