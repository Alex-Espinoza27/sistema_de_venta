<?php


#AQUI SE HACEN TODOS LO METODOS QUE VA UTILIZAR EN EL ARCHIOVO Usuario.php
class UsuariosModel extends Query{
    private $usuario, $nombre,$correo, $clave, $id_caja, $id, $estado;
    public function __construct()  
    {
        #para cargar el constructor de la clase padre
        parent::__construct();
    }
    public function getUsuario(string $usuario, string $clave) {
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave'";
        $data = $this->select($sql);
        return $data;
    }
    public function getUsuarios() {
        #el c.id confunde es por eso que solo muestra el mismo id, lo llamamos con id_caja porque en todo asi estamos usandolo
        $sql = "SELECT u.*, c.id as id_caja, c.caja FROM usuarios u INNER JOIN caja c ON u.id_caja = c.id";
        $data = $this->selectALL($sql);
        return $data;
    }
    public function getCajas() {
        $sql = "SELECT * FROM caja WHERE estado = 1";
        $data = $this->selectALL($sql);
        return $data;
    }
    public function getCajasNombre($id_user) {
        $sql = "SELECT c.caja FROM usuarios u INNER JOIN caja c ON u.id_caja = c.id WHERE u.id = $id_user LIMIT 1";
        $data = $this->select($sql);
        return $data;
    }

    public function registrarUsuario(string $usuario, string $nombre,string $correo, string $clave, int $id_caja) {
        $this->usuario = $usuario;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->id_caja = $id_caja;

        #verificamos si ya existe el usuario
        $verificar = "SELECT * FROM usuarios WHERE usuario = '$this->usuario'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            #si en caso no existe el usuario lo registramos
            $sql = "INSERT INTO usuarios(usuario,nombre,correo,clave,id_caja) VALUES (?,?,?,?,?)";
            $datos = array($this->usuario,$this->nombre,$this->correo,$this->clave,$this->id_caja);
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
    //Esto es para obtener los datos del usuario a edita
    public function editarUser(int $id) {
        $sql = "SELECT * FROM usuarios WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
    public function modificarUsuario(string $usuario, string $nombre,string $correo, int $id_caja, int $id) {
        $this->usuario = $usuario;
        $this->nombre = $nombre; 
        $this->correo = $correo;
        $this->id_caja = $id_caja;
        $this->id = $id;

        $sql = "UPDATE usuarios SET usuario = ?, nombre = ?, correo = ?, id_caja = ? WHERE id = ?";
        $datos = array($this->usuario,$this->nombre,$this->correo,$this->id_caja, $this->id);
        $dato = $this->save($sql, $datos);
        if ($dato == 1) {
            $res = "modificado";
        }
        else{
            $res = "error al modificar el usuario";
        }
        return $res;
    }
    // esto es para que sea para eliminar y reingresar
    public function accionUser(int $id, int $estado) {
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
        $datos = array($this->estado, $this->id);
        $data = $this->save($sql, $datos);
        return $data;
    }
    public function modificarPass(string $clave, int $id){
        $sql = "UPDATE usuarios SET clave = ? WHERE id = ?";
        $datos = array($clave, $id);
        $data = $this->save($sql, $datos);
        return $data;
    }

    // verificamos para modificar la contraseña
    public function getPass(string $clave, int $id) {
        $sql = "SELECT * FROM usuarios WHERE clave = '$clave' AND id = $id";
        $data = $this->select($sql);
        return $data;
    }
    // ------------------------TODOD PERMISOS -------------------------------
    public function getPermisos(){
        $sql = "SELECT * FROM permisos";
        $data = $this->selectALL($sql);
        return $data;
    }
    public function registrarPermisos(int $id_user, int $id_permiso){
        $sql = "INSERT INTO detalle_permisos (id_usuario, id_permiso) VALUES (?,?)";
        $datos = array($id_user, $id_permiso);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }
    public function eliminarPermisos(int $id_user){
        $sql = "DELETE FROM detalle_permisos WHERE id_usuario = ?";
        $datos = array($id_user);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }
    // Esta funcion es para mostrar los permisos ya dados al usuario
    public function getDetallePermisos(int $id_usuario){
        $sql = "SELECT * FROM detalle_permisos WHERE id_usuario = $id_usuario";
        $data = $this->selectALL($sql);
        return $data;
    }
    public function verificarPermiso(int $id_user, string $nombre) {
        $sql = "SELECT  p.id,p.permiso, d.id,d.id_usuario,d.id_permiso FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.permiso = '$nombre'";
        $data = $this->selectALL($sql);
        return $data;
    }
}

?>

