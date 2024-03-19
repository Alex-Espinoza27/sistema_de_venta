<?php
class CategoriasModel extends Query{
    private $nombre, $id, $estado;
    public function __construct()  
    {
        #para cargar el constructor de la clase padre
        parent::__construct();
    }
    public function getCategorias() {
        $sql = "SELECT * FROM categorias";
        $data = $this->selectALL($sql);
        return $data;
    }

    public function registrarCategoria( string $nombre) {
        $this->nombre = $nombre;

        #verificamos si ya existe el dni
        $verificar = "SELECT * FROM categorias WHERE nombre = '$this->nombre'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            #si en caso no existe el nombre lo registramos
            $sql = "INSERT INTO categorias (nombre) VALUES (?)";
            $datos = array($this->nombre);
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
    //Esto es para obtener los datos del dni a edita
    public function editarCategoria(int $id) {
        $sql = "SELECT * FROM categorias WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
    public function modificarCategoria(string $nombre, int $id) {
        $this->nombre = $nombre;   
        $this->id = $id;

        $sql = "UPDATE categorias SET nombre = ? WHERE id = ?";
        $datos = array($this->nombre,$this->id);
        $dato = $this->save($sql, $datos);
        if ($dato == 1) {
            $res = "modificado";
        }
        else{
            $res = "error al modificar la categoria";
        }
        return $res;
    }
    // esto es para que sea para eliminar y reingresar
    public function accionCategoria(int $id, int $estado) {
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE categorias SET estado = ? WHERE id = ?";
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