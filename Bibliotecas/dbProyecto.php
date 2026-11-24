<?php
require_once __DIR__ . '/db.php';

class Proyecto {
    private $con;
    private $id;
    private $programa;
    private $titulo;
    private $objetivoGeneral;
    private $indicadorOG;
    private $recursos;
    private $responsable;
    private $recursosHumanos;
    private $impacto;
    private $observaciones;

    public function __construct() {
        $this->connect_db();
    }

    public function connect_db() {
        $c = new DB();
        $this->con = $c->connection();
    }

    public function getId() { return $this->id; }
    public function getPrograma() { return $this->programa; }
    public function setPrograma($programa) { $this->programa = $programa; }
    public function getTitulo() { return $this->titulo; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function getObjetivoGeneral() { return $this->objetivoGeneral; }
    public function setObjetivoGeneral($objetivoGeneral) { $this->objetivoGeneral = $objetivoGeneral; }
    public function getIndicadorOG() { return $this->indicadorOG; }
    public function setIndicador($indicadorOG) { $this->indicadorOG = $indicadorOG; }
    public function getRecursos() { return $this->recursos; }
    public function setRecursos($recursos) { $this->recursos = $recursos; }
    public function getResponsable() { return $this->responsable; }
    public function setRespnsable($responsable) { $this->responsable = $responsable; } // Se mantiene por compatibilidad
    public function setResponsable($responsable) { $this->responsable = $responsable; }
    public function getRecursosHumanos() { return $this->recursosHumanos; }
    public function setRecursosHumanos($recursosHumanos) { $this->recursosHumanos = $recursosHumanos; }
    public function getImpacto() { return $this->impacto; }
    public function setImpacto($impacto) { $this->impacto = $impacto; }
    public function getObservaciones() { return $this->observaciones; }
    public function setObservaciones($observaciones) { $this->observaciones = $observaciones; }

    public function read($id) {
        $stmt = mysqli_prepare($this->con, "SELECT * FROM proyectos WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $tupla = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if ($tupla) {
            $this->id = $tupla['id'];
            $this->programa = $tupla['programa'];
            $this->titulo = $tupla['titulo'];
            $this->objetivoGeneral = $tupla['objetivoGeneral'];
            $this->indicadorOG = $tupla['indicadorOG'];
            $this->recursos = $tupla['recursos'];
            $this->responsable = $tupla['responsable'];
            $this->recursosHumanos = $tupla['recursosHumanos'];
            $this->impacto = $tupla['impacto'];
            $this->observaciones = $tupla['observaciones'];
            return true;
        }
        return false;
    }

    // Método nuevo limpio (sin HTML) para usar en la etapa de separación de responsabilidades
    public function obtenerActividadesLista() {
        $stmt = mysqli_prepare($this->con, "SELECT id, nombre FROM actividades WHERE proyecto = ?");
        mysqli_stmt_bind_param($stmt, "i", $this->id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        
        $lista = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $lista[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $lista;
    }

    // Se mantienen actividades_S y actividades_M temporalmente usando el método limpio y escapando HTML (XSS)
    public function actividades_S() {
        $lista = $this->obtenerActividadesLista();
        echo "<select class='form-control' name='actividad' id='actividad' required>";
        foreach ($lista as $row) {
            $idAct = htmlspecialchars((string)$row['id'], ENT_QUOTES, 'UTF-8');
            $nombreAct = htmlspecialchars((string)$row['nombre'], ENT_QUOTES, 'UTF-8');
            echo "<option value='{$idAct}'>{$nombreAct}</option>";
        }
        echo "</select>";
        return !empty($lista);
    }

    public function actividades_M() {
        $lista = $this->obtenerActividadesLista();
        foreach ($lista as $row) {
            $idAct = htmlspecialchars((string)$row['id'], ENT_QUOTES, 'UTF-8');
            $nombreAct = htmlspecialchars((string)$row['nombre'], ENT_QUOTES, 'UTF-8');
            echo "<label><input type='checkbox' name='act[]' value='{$idAct}'>{$nombreAct}</label><br>";
        }
        return !empty($lista);
    }

    public function actividades() {
        $stmt = mysqli_prepare($this->con, "SELECT id FROM actividades WHERE proyecto = ?");
        mysqli_stmt_bind_param($stmt, "i", $this->id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $fila = mysqli_fetch_array($res, MYSQLI_BOTH);
        mysqli_stmt_close($stmt);
        return $fila;
    }
}