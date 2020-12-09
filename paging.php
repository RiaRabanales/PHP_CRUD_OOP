<?php
echo "<ul class='pagination'>";
// Herramienta para paginar
 
// Botón de la primera página
if($page>1){
    echo "<li><a href='{$page_url}' title='Ir a la primera página.'>";
        echo "First";
    echo "</a></li>";
}
 
// Calculo el total de páginas y el rango de links que quiero mostrar
$total_pages = ceil($total_rows / $records_per_page);
$range = 2;
 
// Muestro los links del rango de página con los límites del 'if'
$initial_num = $page - $range;
$condition_limit_num = ($page + $range)  + 1;
 
for ($x=$initial_num; $x<$condition_limit_num; $x++) {
    if (($x > 0) && ($x <= $total_pages)) {
 
        // página actual (if) y demás (else)
        if ($x == $page) {
            echo "<li class='active'><a href=\"#\">$x <span class=\"sr-only\">(current)</span></a></li>";
        } else {
            echo "<li><a href='{$page_url}page=$x'>$x</a></li>";
        }
    }
}
 
// Botón para la última página
if ($page<$total_pages){
    echo "<li><a href='" .$page_url. "page={$total_pages}' title='Last page is {$total_pages}.'>";
        echo "Last";
    echo "</a></li>";
}
 
echo "</ul>";
?>