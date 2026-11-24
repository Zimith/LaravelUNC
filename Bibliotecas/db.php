<?php
class DB {
    private $con;
    private $dbhost = "127.0.0.1";
    private $dbuser = "root";
    private $dbpass = "";
    private $dbname = "simep";
    private $dbport = 3307;

    function __construct() {
        $this->connect_db();
    }

    public function connect_db() {
        $this->con = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname, $this->dbport);
        if (mysqli_connect_error()) {
            die("Error de conexión: " . mysqli_connect_error());
        }
        mysqli_set_charset($this->con, "utf8mb4");
    }

    public function connection() {
        return $this->con;
    }
}
?>