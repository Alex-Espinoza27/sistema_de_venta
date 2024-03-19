<?php


#AQUI SE HACEN TODOS LO METODOS QUE VA UTILIZAR EN EL ARCHIOVO dni.php
class ClientesModel extends Query{
    private $dni, $nombre, $telefono, $direccion, $id, $estado;
    public function __construct()  
    {
        #para cargar el constructor de la clase padre
        parent::__construct();
    }
    public function getClientes() {
        #el c.id confunde es por eso que solo muestra el mismo id, lo llamamos con direccion porque en todo asi estamos usandolo
        $sql = "SELECT * FROM clientes";
        $data = $this->selectALL($sql);
        return $data;
    }

    public function registrarCliente(string $dni, string $nombre, string $telefono, string $direccion) {
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->direccion = $direccion;

        #verificamos si ya existe el dni
        $verificar = "SELECT * FROM clientes WHERE dni = '$this->dni'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            #si en caso no existe el dni lo registramos
            $sql = "INSERT INTO clientes (dni,nombre,telefono,direccion) VALUES (?,?,?,?)";
            $datos = array($this->dni,$this->nombre,$this->telefono,$this->direccion);
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
    public function editarCli(int $id) {
        $sql = "SELECT * FROM clientes WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
    public function modificaCliente(string $dni, string $nombre,string $telefono, string $direccion, int $id) {
        $this->dni = $dni;
        $this->nombre = $nombre; 
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->id = $id;

        $sql = "UPDATE clientes SET dni = ?, nombre = ?, telefono = ?, direccion = ? WHERE id = ?";
        $datos = array($this->dni,$this->nombre,$this->telefono,$this->direccion, $this->id);
        $dato = $this->save($sql, $datos);
        if ($dato == 1) {
            $res = "modificado";
        }
        else{
            $res = "error al modificar el dni";
        }
        return $res;
    }
    // esto es para que sea para eliminar y reingresar
    public function accionCli(int $id, int $estado) {
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE clientes SET estado = ? WHERE id = ?";
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