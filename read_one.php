<?php

// Página para ver sólo los datos de un producto
// Primero tomo la id
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

// Incluyo archivos de base de datos y objetos
include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';

// Opero con base de datos: conexión, preparo objetos, seteo ID y leo los detalles
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$category = new Category($db);
$product->id = $id;
$product->readOne();

// Título y cabecera; incluyo footer al final
$page_title = "Read One Product";
include_once "layout_header.php";

// Botón para ver la lista de productos
echo "<div class='right-button-margin'>";
echo "<a href='index.php' class='btn btn-primary pull-right'>";
echo "<span class='glyphicon glyphicon-list'></span>Ver Productos";
echo "</a>";
echo "</div>";

// Genero el HTML de la tabla ara mostrar los detalles del producto:
echo "<table class='table table-hover table-responsive table-bordered'>";

    echo "<tr>";
        echo "<td>Nombre</td>";
        echo "<td>{$product->name}</td>";
    echo "</tr>";

    echo "<tr>";
        echo "<td>Precio</td>";
        echo "<td>\${$product->price}</td>";
    echo "</tr>";

    echo "<tr>";
        echo "<td>Descripción</td>";
        echo "<td>{$product->description}</td>";
    echo "</tr>";

    echo "<tr>";
        echo "<td>Categoría</td>";
        echo "<td>";
        $category->id = $product->category_id;
        $category->readName();
        echo $category->name;
        echo "</td>";
    echo "</tr>";

    echo "<tr>";
        echo "<td>Imagen</td>";
        echo "<td>";
        echo $product->image ? "<img src='uploads/{$product->image}' style='width:300px;' />" : "No image found.";
        echo "</td>";
    echo "</tr>";

echo "</table>";

include_once "layout_footer.php";
?>

