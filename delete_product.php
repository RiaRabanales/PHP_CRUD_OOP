<?php

// Herramienta para eliminar un producto, si estaba en post
if ($_POST) {

    // incluyo archivos
    include_once 'config/database.php';
    include_once 'objects/product.php';

    // opero con base de datos y producto a través de su ID
    $database = new Database();
    $db = $database->getConnection();

    $product = new Product($db);
    $product->id = $_POST['object_id'];

    // Informo si puedo eliminar (if) o no (else)
    if ($product->delete()) {
        echo "Objecto eliminado.";
    } else {
        echo "Imposible eliminar objeto.";
    }
}
?>