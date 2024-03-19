<?php

class Query  extends Conexion{
    private $pdo, $con, $sql ,$datos;
    public function __construct()  {
        $this->pdo = new Conexion();
        $this->con = $this->pdo->conect();
    }
    public function select(string $sql) {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        $resul->execute();
        #solo lista un usuario
        $data = $resul->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
    #Retorna todas as filas
    public function selectALL(string $sql) {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        $resul->execute();
        #solo lista un usuario
        $data = $resul->fetchALL(PDO::FETCH_ASSOC);
        return $data;
    }
    public function save(string $sql, array $datos) {
        $this->sql = $sql;
        $this->datos = $datos;
        $insert = $this->con->prepare($this->sql);
        $data = $insert->execute($this->datos);
        if ($data) {
            $res = 1;
        }
        else{
            $res = 0;
        }
        return $res;
    }
}

?>
