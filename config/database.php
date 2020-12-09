<?php
class Database{
  
    // Aquí los datos de mi base de datos:
    private $host = "localhost";
    private $db_name = "php_oop_crud_level_1";
    private $username = "riarabanales";
    private $password = "alualualu";
    public $conn;
  
    // Método para tomar la conexión
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}
?>