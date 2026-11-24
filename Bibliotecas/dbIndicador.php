<?php
require_once __DIR__ . '/db.php';

class Indicador {
    private $con;
    private $id;
    private $nombre;
    private $proyecto;
    private $objetivoEspecifico;

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
    public function getObjetivoEspecifico() { return $this->objetivoEspecifico; }
    public function setObjetivoEspecífico($objetivoEspecifico) { $this->objetivoEspecifico = $objetivoEspecifico; }
    public function setObjetivoEspecifico($objetivoEspecifico) { $this->objetivoEspecifico = $objetivoEspecifico; }
    public function getProyecto() { return $this->proyecto; }
    public function setProyecto($proyecto) { $this->proyecto = $proyecto; }

    public function Indicadores($proyecto, $objEsp) {
        $stmt = mysqli_prepare($this->con, "SELECT nombre FROM indicadores WHERE proyecto = ? AND objetivoEspecifico = ?");
        mysqli_stmt_bind_param($stmt, "ii", $proyecto, $objEsp);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $rows = [];
        while ($row = mysqli_fetch_array($res)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);

        return !empty($rows) ? $rows : false;
    }

    public function indicadoresXProyecto($proyecto) {
        $stmt = mysqli_prepare($this->con, "SELECT nombre FROM indicadores WHERE proyecto = ?");
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