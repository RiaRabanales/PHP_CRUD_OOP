<?php
// Página principal de mi aplicación; ver tutorial en https://codeofaninja.com/2014/06/php-object-oriented-crud-example-oop.html
// TODO error en lectura de 1
// Incluyo core, con mis variables de paginación:
include_once 'config/core.php';

// Incluyo archivos de base de datos y objetos OOP...
include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';

// ...y los instancio
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$category = new Category($db);

// Incluyo al principio el header y al final el footer
$page_title = "Read Products";
include_once "layout_header.php";

// Hago la query de productos para usar luego
$stmt = $product->readAll($from_record_num, $records_per_page);

// Incluyo el sistema de paginación
$page_url = "index.php?";
$total_rows = $product->countAll();

// Incluyo cómo se verá la lista de productos:
include_once "read_template.php";

// Incluyo el botón de crear:
echo "<div class='right-button-margin'>
    <a href='create_product.php' class='btn btn-default pull-right'>Crear Producto</a>
</div>";

include_once "layout_footer.php";
?>