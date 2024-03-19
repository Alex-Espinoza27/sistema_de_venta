<?php
class CajasModel extends Query
{
    private $nombre, $id, $estado;
    public function __construct()
    {
        parent::__construct();
    }

    // 
    public function getCajas(string $table)
    {
        $sql = "SELECT * FROM $table";
        $data = $this->selectALL($sql);
        return $data;
    }

    public function registrarCaja(string $nombre)
    {
        $this->nombre = $nombre;
        #verificamos si ya existe el nombre
        $verificar = "SELECT * FROM caja WHERE caja = '$this->nombre'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            #si en caso no existe el nombre lo registramos
            $sql = "INSERT INTO caja (caja) VALUES (?)";
            $datos = array($this->nombre);
            #utilizamos la funcion de query
            $dato = $this->save($sql, $datos);
            if ($dato == 1) {
                $res = "ok";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }
        return $res;
    }
    //Esto es para obtener los datos del nombre a editar
    public function editarCaja(int $id)
    {
        $sql = "SELECT * FROM caja WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
    public function modificarCaja(string $nombre, int $id)
    {
        $this->nombre = $nombre;
        $this->id = $id;

        $sql = "UPDATE caja SET caja = ? WHERE id = ?";
        $datos = array($this->nombre, $this->id);
        $dato = $this->save($sql, $datos);
        if ($dato == 1) {
            $res = "modificado";
        } else {
            $res = "error al modificar la caja";
        }
        return $res;
    }
    // esto es para eliminar y reingresar
    public function accionCaja(int $id, int $estado)
    {
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE caja SET estado = ? WHERE id = ?";
        $datos = array($this->estado, $this->id);
        $data = $this->save($sql, $datos);
        return $data;
    }
    // -------------------------------------------TODO ARQUEO --------------------------
    public function registrarArqueo(int $id_usuario, string $monto_inicial, string $fecha_apertura)
    {
        #verificamos si ya existe el usuario
        $verificar = "SELECT * FROM cierre_caja WHERE id_usuario = '$id_usuario' AND estado = 1";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            #si en caso no existe
            $sql = "INSERT INTO cierre_caja (id_usuario,monto_inicial,fecha_apertura) VALUES (?,?,?)";
            $datos = array($id_usuario, $monto_inicial, $fecha_apertura);
            #utilizamos la funcion de query
            $dato = $this->save($sql, $datos);
            if ($dato == 1) {
                $res = "ok"; // la caja se abrio
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }
        return $res;
    }
    public function getVentas(int $id_user)
    {
        // total,
        $sql = "SELECT  SUM(total) AS total FROM ventas WHERE id_usuario = $id_user AND estado = 1 AND apertura = 1";
        $data = $this->select($sql);
        return $data;
    }
    public function getTotalVentas(int $id_user)
    {
        $sql = "SELECT COUNT(total) AS total FROM ventas WHERE id_usuario = $id_user AND estado = 1 AND apertura = 1";
        $data = $this->select($sql);
        return $data;
    }
    public function getMontoInicial(int $id_user)
    {
        $sql = "SELECT  id, monto_inicial FROM cierre_caja WHERE id_usuario = $id_user AND estado = 1";
        $data = $this->select($sql);
        return $data;
    }
    public function actualizarArqueo(string $monto_final, string $cierre, string $total_ventas, string $general, int $id)
    {
        $sql = "UPDATE cierre_caja SET monto_final = ?,fecha_cierre = ?, total_ventas = ?, monto_total =?, estado = ? WHERE id= ?";
        $datos = array($monto_final, $cierre, $total_ventas, $general,0, $id);
        $dato = $this->save($sql, $datos);  
        if ($dato == 1) {
            $res = "ok"; // la caja se abrio
        } else {
            $res = "error";
        }
        return $res;
    }
    public function actualizarApertura(int $id)
    {
        $sql = "UPDATE ventas SET apertura = ? WHERE id_usuario= ?";
        $datos = array(0, $id);
        $this->save($sql, $datos);
    }
    public function getVerificarEstadoCaja(int $id_user)
    {
        $sql = "SELECT * FROM cierre_caja WHERE estado = 1 AND id_usuario = $id_user";
        $data = $this->select($sql);
        return $data;
    }
    public function verificarPermiso(int $id_user, string $nombre) {
        $sql = "SELECT  p.id,p.permiso, d.id,d.id_usuario,d.id_permiso FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.permiso = '$nombre'";
        $data = $this->selectALL($sql);
        return $data;
    }

}

?>