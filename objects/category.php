<?php

class Category {

    // Conexión a la base de datos con su tabla
    private $conn;
    private $table_name = "categories";
    // Propiedades del objeto
    public $id;
    public $name;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método que usaré al generar el menú drop-down:
    function read() {
        // selecciono todos los datos
        $query = "SELECT id, name FROM " . $this->table_name . " ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Método que me devolverá el nombre de categoría y no sólo un ID:
    function readName() {
        $query = "SELECT name FROM " . $this->table_name . " WHERE id = ? limit 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->name = $row['name'];             //recupero el nombre
    }

}

?>