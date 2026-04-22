<?php
class db {
    private $hostname = 'localhost';
    private $bancodedados = 'ecociclodb';
    private $usuario = 'root';
    private $senha = '';
    private $mysqli = null;

    public function conecta_mysql() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->mysqli = new mysqli(
                $this->hostname,
                $this->usuario,
                $this->senha,
                $this->bancodedados
            );

            $this->mysqli->set_charset('utf8mb4');

            return $this->mysqli;
        } catch (mysqli_sql_exception $e) {
            die("Falha na conexão com o banco: " . $e->getMessage());
        }
    }
}
?>