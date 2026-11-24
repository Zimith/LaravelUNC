<?php
require_once __DIR__ . '/db.php';

class Avance {
    private $con;
    private $id;
    private $proyecto;
    private $actividad;
    private $fecha;
    private $fuenteValidacion;
    private $estado;
    private $observaciones;
    private $justificacion;
    private $usuario;
    private $inicio;
    private $fin;
    private $activo;

    public function __construct() {
        $this->connect_db();
    }

    public function connect_db() {
        $c = new DB();
        $this->con =$c->connection();
    }

    public function getId() { return $this->id; }
    public function getProyecto() { return $this->proyecto; }
    public function setProyecto($proyecto) { $this->proyecto =$proyecto; }
    public function getActividad() { return $this->actividad; }
    public function setActividad($actividad) { $this->actividad =$actividad; }
    public function getFecha() { return $this->fecha; }
    public function setFecha($fecha) { $this->fecha =$fecha; }
    public function getFuenteValidacion() { return $this->fuenteValidacion; }
    public function setFuenteValidacion($fuenteValidacion) { $this->fuenteValidacion =$fuenteValidacion; }
    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado =$estado; }
    public function getObservaciones() { return $this->observaciones; }
    public function setObservaciones($observaciones) { $this->observaciones =$observaciones; }
    public function getJustificacion() { return $this->justificacion; }
    public function setJustificacion($justificacion) { $this->justificacion =$justificacion; }
    public function getUsuario() { return $this->usuario; }
    public function setUsuario($usuario) { $this->usuario =$usuario; }
    public function getInicio() { return $this->inicio; }
    public function setInicio($inicio) { $this->inicio =$inicio; }
    public function getFin() { return $this->fin; }
    public function setFin($fin) { $this->fin =$fin; }
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo =$activo; }

    public function sanitize($var) {
        return mysqli_real_escape_string($this->con, (string)$var);
    }

    private function cargarDesdeTupla(array $tupla) {
        $this->id =$tupla['id'];
        $this->proyecto =$tupla['proyecto'];
        $this->actividad =$tupla['actividad'];
        $this->fecha =$tupla['fecha'];
        $this->fuenteValidacion =$tupla['fuenteValidacion'];
        $this->estado =$tupla['estado'];
        $this->observaciones =$tupla['observaciones'];
        $this->justificacion =$tupla['justificacion'];
        $this->usuario =$tupla['usuario'];
        $this->inicio =$tupla['inicio'];
        $this->fin =$tupla['fin'];
        $this->activo =$tupla['activo'];
    }

    public function read($id) {
        $stmt = mysqli_prepare($this->con, "SELECT * FROM grados_avance WHERE id = ? AND activo = 1");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $tupla = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if ($tupla) {
            $this->cargarDesdeTupla($tupla);
            return true;
        }
        return false;
    }

    public function readXActividad($id) {
        $stmt = mysqli_prepare($this->con, "SELECT * FROM grados_avance WHERE actividad = ? AND activo = 1");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $tupla = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if ($tupla) {
            $this->cargarDesdeTupla($tupla);
            return true;
        }
        return false;
    }

    public function readXProyecto($id) {
        $stmt = mysqli_prepare($this->con, "SELECT * FROM grados_avance WHERE proyecto = ? AND activo = 1 ORDER BY fecha DESC");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $rows = [];
        while ($row = mysqli_fetch_array($res)) {
            $rows[] =$row;
        }
        mysqli_stmt_close($stmt);

        return !empty($rows) ?$rows : false;
    }

    public function create() {
        $sql = "INSERT INTO grados_avance (proyecto, actividad, fecha, fuenteValidacion, estado, observaciones, justificacion, usuario, inicio, fin) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->con,$sql);
        mysqli_stmt_bind_param(
            $stmt,
            "iisssssiss",
            $this->proyecto,$this->actividad,
            $this->fecha,$this->fuenteValidacion,
            $this->estado,$this->observaciones,
            $this->justificacion,$this->usuario,
            $this->inicio,$this->fin
        );
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function update() {
        $sql = "UPDATE grados_avance 
                SET proyecto = ?, actividad = ?, fecha = ?, fuenteValidacion = ?, estado = ?, 
                    observaciones = ?, justificacion = ?, usuario = ?, inicio = ?, fin = ?, activo = ? 
                WHERE id = ?";
        $stmt = mysqli_prepare($this->con,$sql);
        mysqli_stmt_bind_param(
            $stmt,
            "iisssssissii",
            $this->proyecto,$this->actividad,
            $this->fecha,$this->fuenteValidacion,
            $this->estado,$this->observaciones,
            $this->justificacion,$this->usuario,
            $this->inicio,$this->fin,
            $this->activo,$this->id
        );
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function delete() {
        $stmt = mysqli_prepare($this->con, "UPDATE grados_avance SET activo = 0 WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $this->id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
}