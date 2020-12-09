<?php
// Incluyo la base de datos y objetos POO:
include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';

// Tomo conexión a base de datos y la paso a los objetos:
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$category = new Category($db);

// Establecer headers de página e incluir header
$page_title = "Create Product";
include_once "layout_header.php";

echo "<div class='right-button-margin'>
        <a href='index.php' class='btn btn-default pull-right'>Ver Productos</a>
    </div>";

// Código $_POST en php, si se ha enviado el formulario:
if ($_POST) {

    // introduzco los valores de las propiedades del producto
    $product->name = $_POST['name'];
    $product->price = $_POST['price'];
    $product->description = $_POST['description'];
    $product->category_id = $_POST['category_id'];
    // Nota: ¿por qué hace directamente el insert de la imagen antes de verificar? ¿No debería ser al revés? Ver líneas 33ss.
    $image = !empty($_FILES["image"]["name"]) ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"]) : "";
    $product->image = $image;

    // creo el producto, y si no puedo se lo digo al usuario
    if ($product->create()) {
        // con esto manejo la imagen; el método está en producto
        echo $product->uploadPhoto();
        echo "<div class='alert alert-success'>Producto creado.</div>";
    } else {
        echo "<div class='alert alert-danger'>Imposible crear producto.</div>";
    }
}
?>

<!-- Formulario HTML para crear el producto: preparado para subir archivos (apt 11) -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

    <table class='table table-hover table-responsive table-bordered'>

        <tr>
            <td>Nombre</td>
            <td><input type='text' name='name' class='form-control' /></td>
        </tr>

        <tr>
            <td>Precio</td>
            <td><input type='text' name='price' class='form-control' /></td>
        </tr>

        <tr>
            <td>Descripción</td>
            <td><textarea name='description' class='form-control'></textarea></td>
        </tr>

        <tr>
            <td>Categoría</td>
            <td>
                <!-- Aquí incluyo un loop con las categoría de la base de datos -->
                <?php
                // las leo de la base de datos...
                $stmt = $category->read();

                // ...y las pongo en un drop-down
                echo "<select class='form-control' name='category_id'>";
                echo "<option>Select category...</option>";

                while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row_category);
                    echo "<option value='{$id}'>{$name}</option>";
                }

                echo "</select>";
                ?>
            </td>
        </tr>

        <tr>
            <td>Foto</td>
            <td><input type="file" name="image" /></td>
        </tr>

        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">Crear</button>
            </td>
        </tr>

    </table>
</form>

<?php
// Incluir footer
include_once "layout_footer.php";
?>
