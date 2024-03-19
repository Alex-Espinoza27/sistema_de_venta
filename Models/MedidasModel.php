<?php


#AQUI SE HACEN TODOS LO METODOS QUE VA UTILIZAR EN EL ARCHIOVO dni.php
class MedidasModel extends Query{
    private $nombre, $nombre_corto, $id, $estado;
    public function __construct()  
    {
        #para cargar el constructor de la clase padre
        parent::__construct();
    }
    public function getMedidas() {
        #el c.id confunde es por eso que solo muestra el mismo id, lo llamamos con direccion porque en todo asi estamos usandolo
        $sql = "SELECT * FROM medidas";
        $data = $this->selectALL($sql);
        return $data;
    }

    public function registrarMedida( string $nombre, string $nombre_corto) {
        $this->nombre = $nombre; 
        $this->nombre_corto = $nombre_corto; 

        #verificamos si ya existe el dni
        $verificar = "SELECT * FROM medidas WHERE nombre = '$this->nombre'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            #si en caso no existe el dni lo registramos
            $sql = "INSERT INTO medidas (nombre,nombre_corto) VALUES (?,?)";
            $datos = array($this->nombre,$this->nombre_corto);
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
    public function editarMed(int $id) {
        $sql = "SELECT * FROM medidas WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
    public function modificaMedida(string $nombre, string $nombre_corto, int $id) {
        $this->nombre = $nombre;  
        $this->nombre_corto = $nombre_corto; 
        $this->id = $id;

        $sql = "UPDATE medidas SET nombre = ?, nombre_corto = ? WHERE id = ?";
        $datos = array($this->nombre,$this->nombre_corto, $this->id);
        $dato = $this->save($sql, $datos);
        if ($dato == 1) {
            $res = "modificado";
        }
        else{
            $res = "error al modificar la medida";
        }
        return $res;
    }
    // esto es para que sea para eliminar y reingresar
    public function accionMed(int $id, int $estado) {
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE medidas SET estado = ? WHERE id = ?";
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