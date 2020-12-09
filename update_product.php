<?php
// Herramienta para actualizar sólo un producto
// Aaquí recupero un producto a partir de su id:
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: falta ID.');

// incluyo archivos necesarios
include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';

// hago la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// preparo los objetos OOP, seteo la ID del producto  a editar y leo los detalles
$product = new Product($db);
$category = new Category($db);
$product->id = $id;
$product->readOne();

// Seteo el header de página
$page_title = "Update Product";
include_once "layout_header.php";

// Creo un botón para ver productos:
echo "<div class='right-button-margin'>
          <a href='index.php' class='btn btn-default pull-right'>Ver Productos</a>
     </div>";

// Aquí el php para cuando el formulario se envía:
if ($_POST) {
    $product->name = $_POST['name'];
    $product->price = $_POST['price'];
    $product->description = $_POST['description'];
    $product->category_id = $_POST['category_id'];
    $image = !empty($_FILES["image"]["name"]) ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"]) : "";
    $product->image = $image;

    // Informo si lo hago o no:
    if ($product->update()) {
        echo $product->uploadPhoto();
        echo "<div class='alert alert-success alert-dismissable'>";
        echo "El producto se ha actualizado.";
        echo "</div>";
    } else {
        echo "<div class='alert alert-danger alert-dismissable'>";
        echo "El producto no se ha podido actualizar.";
        echo "</div>";
    }
}
?>

<!-- Formulario para los valores -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>

        <tr>
            <td>Nombre</td>
            <td><input type='text' name='name' value='<?php echo $product->name; ?>' class='form-control' /></td>
        </tr>

        <tr>
            <td>Precio</td>
            <td><input type='text' name='price' value='<?php echo $product->price; ?>' class='form-control' /></td>
        </tr>

        <tr>
            <td>Descripción</td>
            <td><textarea name='description' class='form-control'><?php echo $product->description; ?></textarea></td>
        </tr>

        <tr>
            <td>Categoría</td>
            <td>
                <!-- Aquí el select drop-down con las categorías -->
                <?php
                $stmt = $category->read();

                echo "<select class='form-control' name='category_id'>";

                echo "<option>Please select...</option>";
                while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $category_id = $row_category['id'];
                    $category_name = $row_category['name'];

                    // y me aseguro de seleccionar la categoría actual del producto
                    if ($product->category_id == $category_id) {
                        echo "<option value='$category_id' selected>";
                    } else {
                        echo "<option value='$category_id'>";
                    }

                    echo "$category_name</option>";
                }
                echo "</select>";
                ?>
            </td>
        </tr>
        
        <tr>
            <td>Foto</td>
            <td><input type='file' name='image' value='<?php echo $product->image; ?>' class='form-control' /></td>
        </tr>

        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </td>
        </tr>

    </table>
</form>

<?php
// Incluyo el pie de página; añado tag al tutorial porque falta
include_once "layout_footer.php";
?>

