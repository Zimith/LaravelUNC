<?php
require_once __DIR__ . '/db.php';

class ObjetivoEspecifico {
    private $con;
    private $id;
    private $nombre;
    private $descripcion;
    private $proyecto;

    public function __construct() {
        $this->connect_db();
    }

    public function connect_db() {
        $c = new DB();
        $this->con = $c->connection();
    }

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function getProyecto() { return $this->proyecto; }
    public function setProyecto($proyecto) { $this->proyecto = $proyecto; }

    public function objetivosEspecificos($proyecto) {
        $stmt = mysqli_prepare($this->con, "SELECT nombre, id FROM objetivos_especificos WHERE proyecto = ?");
        mysqli_stmt_bind_param($stmt, "i", $proyecto);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $rows = [];
        while ($row = mysqli_fetch_array($res)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);

        return !empty($rows) ? $rows : false;
    }
}