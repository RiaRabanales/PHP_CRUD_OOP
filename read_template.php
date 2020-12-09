<?php

// Esto es un template para poder repetir  la forma de búsqueda en index y search
//Primero el formulario de búsqueda:
echo "<form role='search' action='search.php'>";
echo "<div class='input-group col-md-3 pull-left margin-right-1em'>";
$search_value = isset($search_term) ? "value='{$search_term}'" : "";
echo "<input type='text' class='form-control' placeholder='Escribe el nombre o descripción del producto...' name='s' id='srch-term' required {$search_value} />";
echo "<div class='input-group-btn'>";
echo "<button class='btn btn-primary' type='submit'><i class='glyphicon glyphicon-search'></i></button>";
echo "</div>";
echo "</div>";
echo "</form>";

// Botón para crear productos
echo "<div class='right-button-margin'>";
echo "<a href='create_product.php' class='btn btn-primary pull-right'>";
echo "<span class='glyphicon glyphicon-plus'></span> Crear Producto";
echo "</a>";
echo "</div>";

// Si hay productos los muestro (if) y sino doy un aviso (else)
if ($total_rows > 0) {

    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr>";
    echo "<th>Producto</th>";
    echo "<th>Precio</th>";
    echo "<th>Descripcion</th>";
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
        // Botón de ver producto
        echo "<a href='read_one.php?id={$id}' class='btn btn-primary left-margin'>";
        echo "<span class='glyphicon glyphicon-list'></span> Ver";
        echo "</a>";
        // Botón de ver producto
        echo "<a href='update_product.php?id={$id}' class='btn btn-info left-margin'>";
        echo "<span class='glyphicon glyphicon-edit'></span> Editar";
        echo "</a>";
        // Botón de ver producto
        echo "<a delete-id='{$id}' class='btn btn-danger delete-object'>";
        echo "<span class='glyphicon glyphicon-remove'></span> Eliminar";
        echo "</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";

    // Botones de paginación
    include_once 'paging.php';
} else {
    echo "<div class='alert alert-danger'>No se han encontrado productos.</div>";
}
?>
