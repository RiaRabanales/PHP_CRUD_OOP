<?php
// Página principal de mi aplicación; ver tutorial en https://codeofaninja.com/2014/06/php-object-oriented-crud-example-oop.html
// Esta es la versión previa al punto 10.1; la guardo por lo clara que es.
// El número de página se pasa por URL; default es 1 (operador ternario)
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Límite de registros por página y operación para cada página:
$records_per_page = 5;
$from_record_num = ($records_per_page * $page) - $records_per_page;

// Incluyo archivos de base de datos y objetos OOP...
include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';

// ...y los instancio
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$category = new Category($db);

// Hago la query:
$stmt = $product->readAll($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// Incluyo al principio el header y al final el footer
$page_title = "Read Products";
include_once "layout_header.php";

echo "<div class='right-button-margin'>
    <a href='create_product.php' class='btn btn-default pull-right'>Crear Producto</a>
</div>";

// Si hay productos los muestro:
if ($num > 0) {

    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr>";
    echo "<th>Producto</th>";
    echo "<th>Precio</th>";
    echo "<th>Descripción</th>";
    echo "<th>Categoría</th>";
    echo "<th>Acciones</th>";
    echo "</tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        echo "<tr>";
            echo "<td>{$name}</td>";
            echo "<td>{$price}</td>";
            echo "<td>{$description}</td>";
            echo "<td>";
            $category->id = $category_id;
            $category->readName();
            echo $category->name;
            echo "</td>";
            echo "<td>";
            
            // Botones de leer, editar y eliminar:
            echo "<a href='read_one.php?id={$id}' class='btn btn-primary left-margin'>
                <span class='glyphicon glyphicon-list'></span> Leer
            </a>
            <a href='update_product.php?id={$id}' class='btn btn-info left-margin'>
                <span class='glyphicon glyphicon-edit'></span> Editar
            </a>
            <a delete-id='{$id}' class='btn btn-danger delete-object'>
                <span class='glyphicon glyphicon-remove'></span> Eliminar
            </a>";
            echo "</td>";
        echo "</tr>";
    }

    echo "</table>";

    // Incluyo el sistema de paginación
    $page_url = "index.php?";
    $total_rows = $product->countAll();
    include_once 'paging.php';
    
} else {
    // Si no, informo al usuario de que no hay productos
    echo "<div class='alert alert-info'>No se han encontrado productos.</div>";
}

include_once "layout_footer.php";
?>