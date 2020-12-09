<?php
// Herramienta para mostrar los resultados según lo que busque el usuario

// Incluyo archivos necesarios
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';
 
// Opero con base de datos y objetos
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$category = new Category($db);
 
// Si existe, cojo el término de búsqueda
$search_term=isset($_GET['s']) ? $_GET['s'] : '';
 
$page_title = "Búsqueda por: \"{$search_term}\"";
include_once "layout_header.php";
 
// Hago la query
$stmt = $product->search($search_term, $from_record_num, $records_per_page);
 
// Especifico la página para el paginado
$page_url="search.php?s={$search_term}&";
 
// Cuento filas para paginación
$total_rows=$product->countAll_BySearch($search_term);
 
// Incluyo archivos básicos
include_once "read_template.php";
include_once "layout_footer.php";
?>