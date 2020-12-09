<?php
// Incluye mis variables de paginación (originalmente en index y paging.php)

// Tomo la página por URL (default es 1, ver operador ternario
$page = isset($_GET['page']) ? $_GET['page'] : 1; 
 
// Establezco los resultados por página
$records_per_page = 5;
 
// Cálculo para hacer el LIMIT en el query
$from_record_num = ($records_per_page * $page) - $records_per_page;
?>
