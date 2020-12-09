<?php

class Product {

    // Conexión y tabla de la base de datos
    private $conn;
    private $table_name = "products";
    // Propiedades del objeto
    public $id;
    public $name;
    public $price;
    public $description;
    public $category_id;
    public $image;
    public $timestamp;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para crear el producto en la base de datos
    function create() {

        // incluyendo la imagen:
        $query = "INSERT INTO " . $this->table_name . "
            SET name=:name, price=:price, description=:description,
            category_id=:category_id, image=:image, created=:created";

        $stmt = $this->conn->prepare($query);

        // sanitizo los valores y tomo el timestamp para la fecha
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->timestamp = date('Y-m-d H:i:s');

        // bindeo los valores
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":created", $this->timestamp);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Método para poderrecoger todos los registros de productos en mi página principal
    function readAll($from_record_num, $records_per_page) {

        $query = "SELECT id, name, description, price, category_id
            FROM " . $this->table_name . "
            ORDER BY name ASC
            LIMIT {$from_record_num}, {$records_per_page}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para contar el total de productos en mi pase de datos:
    public function countAll() {
        $query = "SELECT id FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();

        return $num;
    }

    //Método para poder 'leer' sólo un producto:
    function readOne() {

        $query = "SELECT name, price, description, category_id
            FROM " . $this->table_name . "
            WHERE id = ?
            LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->price = $row['price'];
        $this->description = $row['description'];
        $this->category_id = $row['category_id'];
        //$this->image = $row['image'];
    }

    // Método para poder actualizar un producto en la base de datos:
    function update() {

        $query = "UPDATE " . $this->table_name . "
            SET
                name = :name,
                price = :price,
                description = :description,
                category_id  = :category_id
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizo los valores:
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bindeo los parámetros:
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        // Por último ejecuto la consulta:
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para poder eliminar un producto:
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($result = $stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Método para hacer búsquedas y poder leer productos (TODO: revisar el left join)
    public function search($search_term, $from_record_num, $records_per_page) {

        $query = "SELECT
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categories c
                        ON p.category_id = c.id
            WHERE
                p.name LIKE ? OR p.description LIKE ?
            ORDER BY
                p.name ASC
            LIMIT
                ?, ?";

        $stmt = $this->conn->prepare($query);

        // bindeo las variables
        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);
        $stmt->bindParam(2, $search_term);
        $stmt->bindParam(3, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(4, $records_per_page, PDO::PARAM_INT);

        // ejecuto la query y recupero el resultado
        $stmt->execute();
        return $stmt;
    }

    // Método para saber cuántos resultados de búsqueda tengo
    public function countAll_BySearch($search_term) {

        $query = "SELECT COUNT(*) as total_rows
            FROM " . $this->table_name . " p 
            WHERE p.name LIKE ? OR p.description LIKE ?";

        $stmt = $this->conn->prepare($query);

        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);
        $stmt->bindParam(2, $search_term);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    // Método para subir la imagen; querré que me devuelva un mensaje de error si no puede.
    function uploadPhoto() {
        $result_message = "";

        // Si la imagen no está vacía intento subirla
        if ($this->image) {
            // sha1_file() se usa para crear nombre único
            $target_directory = "uploads/";
            $target_file = $target_directory . $this->image;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // el mensaje de error está vacío...
            $file_upload_error_messages = "";

            //con esto valido la imagen:
            // compruebo que sea una imagen real...
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                // submitted file is an imageno hago nada porque efectivamente es una imagen
            } else {
                $file_upload_error_messages .= "<div>El archivo no es una imagen.</div>";
            }

            // ... compruebo que sea de una extensión que permito ...
            $allowed_file_types = array("jpg", "jpeg", "png", "gif");
            if (!in_array($file_type, $allowed_file_types)) {
                $file_upload_error_messages .= "<div>Sólo extensiones JPG, JPEG, PNG, GIF permitidas.</div>";
            }

            // ... compruebo que no exista ya el archivo ...
            if (file_exists($target_file)) {
                $file_upload_error_messages .= "<div>La imagen ya existe.</div>";
            }

            //compruebo que no sea mayor que 1 MB...
            if ($_FILES['image']['size'] > (1024000)) {
                $file_upload_error_messages .= "<div>La imagen debe ocupar menos de 1MB.</div>";
            }

            // miro si existe la carpeta 'uploads' y si no la creo
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0777, true);
            }

            // Si es válida la subo al servidor; esto lo miro por si $file_upload_error_messages está vacío
            if (empty($file_upload_error_messages)) {
                // está vacío así que intento subir
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // subo bien la foto
                } else {
                    $result_message .= "<div class='alert alert-danger'>";
                    $result_message .= "<div>Imposible subir imagen.</div>";
                    $result_message .= "</div>";
                }
            } else {
                // si no está vacío hay errores, así que hay que enseñarlos.
                $result_message .= "<div class='alert alert-danger'>";
                $result_message .= "{$file_upload_error_messages}";
                $result_message .= "</div>";
            }
        }
        return $result_message;
    }

}

?>