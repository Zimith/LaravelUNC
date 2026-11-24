<?php
require_once __DIR__ . '/db.php';

class Login {
    private $con;

    public function __construct() {
        $this->connect_db();
    }

    public function connect_db() {
        $c = new DB();
        $this->con = $c->connection();
    }

    public function userExist($user, $pass) {
        // Se usa '=' en lugar de 'LIKE' y sentencia preparada para evitar inyección SQL
        $sql = "SELECT username, password, tipo, id FROM usuarios WHERE username = ? AND password = ?";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $user, $pass);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        
        // Devuelve el objeto mysqli_result para mantener compatibilidad con comprueba.php
        return $res;
    }
}