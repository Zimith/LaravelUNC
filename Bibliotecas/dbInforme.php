<?php
require_once __DIR__ . '/db.php';

class Informe {
    private $con;
    private $id;
    private $proyecto;
    private $informe;

    public function __construct() {
        $this->connect_db();
    }

    public function connect_db() {
        $c = new DB();
        $this->con = $c->connection();
    }

    public function getId() { return $this->id; }
    public function getProyecto() { return $this->proyecto; }
    public function setProyecto($proyecto) { $this->proyecto = $proyecto; }
    public function getInforme() { return $this->informe; }
    public function setInforme($informe) { $this->informe = $informe; }

    public function sanitize($var) {
        return mysqli_real_escape_string($this->con, (string)$var);
    }

    public function read($id) {
        $stmt = mysqli_prepare($this->con, "SELECT id, proyecto, informe FROM informe_avance WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $tupla = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if ($tupla) {
            $this->id = $tupla['id'];
            $this->proyecto = $tupla['proyecto'];
            $this->informe = $tupla['informe'];
            return true;
        }
        return false;
    }

    public function create() {
        $stmt = mysqli_prepare($this->con, "INSERT INTO informe_avance (proyecto, informe) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "is", $this->proyecto, $this->informe);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function update() {
        // Corregido: usaba $this->actividad (inexistente) en lugar de $this->informe
        $stmt = mysqli_prepare($this->con, "UPDATE informe_avance SET proyecto = ?, informe = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "isi", $this->proyecto, $this->informe, $this->id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function delete() {
        $stmt = mysqli_prepare($this->con, "UPDATE informe_avance SET activo = 0 WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $this->id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
}