<?php
require_once __DIR__ . '/db.php';

class Usuario {
    private $con;
    private $id;
    private $username;
    private $password;
    private $nombre;
    private $apellido;
    private $email;
    private $tipo;
    private $descripcion;

    public function __construct() {
        $this->connect_db();
    }

    public function connect_db() {
        $c = new DB();
        $this->con = $c->connection();
    }

    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function setUsername($username) { $this->username = $username; }
    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function getApellido() { return $this->apellido; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }
    public function getTipo() { return $this->tipo; }
    public function setTipo($tipo) { $this->tipo = $tipo; }
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getNombreCompleto() {
        return $this->nombre . ", " . $this->apellido;
    }

    public function read($id) {
        $stmt = mysqli_prepare($this->con, "SELECT id, username, password, nombre, apellido, email, tipo, descripcion FROM usuarios WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $tupla = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if ($tupla) {
            $this->id = $tupla['id'];
            $this->username = $tupla['username'];
            $this->password = $tupla['password'];
            $this->nombre = $tupla['nombre'];
            $this->apellido = $tupla['apellido'];
            $this->email = $tupla['email'];
            $this->tipo = $tupla['tipo'];
            $this->descripcion = $tupla['descripcion'];
            return true;
        }
        return false;
    }

    public function proyectos($user) {
        $sql = "SELECT p.titulo, p.id 
                FROM proyectos p 
                INNER JOIN usuario_x_proyecto uxp ON p.id = uxp.proyecto 
                WHERE uxp.usuario = ?";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user);
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