<?php
require_once __DIR__ . '/db.php';

class Actividad {
    private $con;
    private $id;
    private $nombre;
    private $inicio;
    private $inicio_t;
    private $fin;
    private $fin_t;
    private $inicio2;
    private $inicio_t2;
    private $fin2;
    private $fin_t2;
    private $tipo;
    private $frecuencia;
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
    public function getInicio() { return $this->inicio; }
    public function setInicio($inicio) { $this->inicio = $inicio; }
    public function getInicio_t() { return $this->inicio_t; }
    public function setInicio_t($inicio_t) { $this->inicio_t = $inicio_t; }
    public function getFin() { return $this->fin; }
    public function setFin($fin) { $this->fin = $fin; }
    public function getFin_t() { return $this->fin_t; }
    public function setFin_t($fin_t) { $this->fin_t = $fin_t; }
    public function getInicio2() { return $this->inicio2; }
    public function setInicio2($inicio) { $this->inicio2 = $inicio; }
    public function getInicio_t2() { return $this->inicio_t2; }
    public function setInicio_t2($inicio_t2) { $this->inicio_t2 = $inicio_t2; }
    public function getFin2() { return $this->fin2; }
    public function setFin2($fin) { $this->fin2 = $fin; }
    public function getFin_t2() { return $this->fin_t2; }
    public function setFin_t2($fin_t2) { $this->fin_t2 = $fin_t2; }
    public function getTipo() { return $this->tipo; }
    public function setTipo($tipo) { $this->tipo = $tipo; }
    public function getFrecuencia() { return $this->frecuencia; }
    public function setFrecuencia($frecuencia) { $this->frecuencia = $frecuencia; }
    public function getObjetivoEspecifico() { return $this->objetivoEspecifico; }
    public function setObjetivosEspecifico($objetivoEspecifico) { $this->objetivoEspecifico = $objetivoEspecifico; }
    public function getProyecto() { return $this->proyecto; }
    public function setProyecto($proyecto) { $this->proyecto = $proyecto; }

    private function formatearTrimestre($valor, $conDelAnio = false) {
        $num = (int)$valor;
        if ($num <= 0) {
            return "Sin definir";
        }
        $trimestre = intdiv($num, 100);
        $anio = $num % 100;
        $textoAnio = $conDelAnio ? "del año 20" : "de 20";
        return "{$trimestre}º trimestre {$textoAnio}{$anio}";
    }

    public function getInicioHumano() { return $this->formatearTrimestre($this->inicio, true); }
    public function getFinHumano() { return $this->formatearTrimestre($this->fin, true); }
    public function getInicioHumano2() { return $this->formatearTrimestre($this->inicio2, false); }
    public function getFinHumano2() { return $this->formatearTrimestre($this->fin2, false); }
    public function inicioHumano($ini) { return $this->formatearTrimestre($ini, false); }
    public function finHumano($fin) { return $this->formatearTrimestre($fin, false); }

    public function read($act) {
        $stmt = mysqli_prepare($this->con, "SELECT * FROM actividades WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $act);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $tupla = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if ($tupla) {
            $this->id = $tupla['id'];
            $this->nombre = $tupla['nombre'];
            $this->inicio = $tupla['inicio'];
            $this->inicio_t = $tupla['inicio_t'];
            $this->fin = $tupla['fin'];
            $this->fin_t = $tupla['fin_t'];
            $this->inicio2 = $tupla['inicio2'];
            $this->inicio_t2 = $tupla['inicio_t2'];
            $this->fin2 = $tupla['fin2'];
            $this->fin_t2 = $tupla['fin_t2'];
            $this->tipo = $tupla['tipo'];
            $this->frecuencia = $tupla['frecuencia'];
            $this->proyecto = $tupla['proyecto'];
            $this->objetivoEspecifico = $tupla['objetivoEspecifico']; // Corregido typo en nombre de propiedad
            return true;
        }
        return false;
    }

    public function actividadesXProyecto($proyecto) {
        $stmt = mysqli_prepare($this->con, "SELECT nombre FROM actividades WHERE proyecto = ?");
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

    public function actividadesXProyecto_A($proyecto) {
        $stmt = mysqli_prepare($this->con, "SELECT * FROM actividades WHERE proyecto = ?");
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

    public function actividadesXObjetivoEspecifico($objetivoE) {
        $stmt = mysqli_prepare($this->con, "SELECT nombre, inicio, fin, inicio2, fin2, id, tipo FROM actividades WHERE objetivoEspecifico = ?");
        mysqli_stmt_bind_param($stmt, "i", $objetivoE);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $rows = [];
        while ($row = mysqli_fetch_array($res)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);

        return !empty($rows) ? $rows : false;
    }

    public function estado($id) {
        $stmt = mysqli_prepare($this->con, "SELECT estado FROM grados_avance WHERE actividad = ? AND activo = 1 ORDER BY id DESC LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($res);
        mysqli_stmt_close($stmt);

        return $row ? $row[0] : false;
    }
}