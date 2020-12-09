# Repositorio:


## 1.
Para que en este código figure el símbolo '$' a la izquierda del precio (en el listado de productos y en el detalle) simplemente he tenido que añadir el símbolo apropiado al string que corresponde.
1a. En el archivo index.php no he tenido que hacer cambios, sino que he podido trabajar directamente con read_template.php, ya que el primero incluye al segundo:
En read_template.php, por tanto, he añadido, en la línea 39 el símbolo de dólar de la siguiente manera:
> echo "<td>$ {$price}</td>";
1b. En el archivo read_one.php he hecho el mismo tipo de cambio en la línea 41, pero con una solución algo diferente; en lugar de 'dólar + espacio' he empleado 'contrabarra + dólar':
> echo "<td>\$ {$product->price}</td>";
Otra posible solución hubiera sido emplear comillas simples en el dólar.

## 2.
El código que define cómo se guarda un producto está en el archivo create_product.php, pero los criterios que definen cómo se guarda una imagen están en la función uploadPhoto() en el archivo objects/product.php.

Cabe destacar que lo que guardo en la base de datos no es la imagen en sí, sino la referencia al nombre de la imagen, que al generarse con *SHA* (obteniendo su firma digital) y concatenar luego el nombre de la imagen me garantiza que sea única.

El proceso que sigue este método comienza declarando una variable con el mensaje que se dará al usuario; esta variable es:
$result_message = "";

Si la imagen no está vacía intento subirla en un directorio llamado 'uploads' y que trataré durante la función, con el siguiente fragmento de código.
        if ($this->image) {       
            $target_directory = "uploads/";
            $target_file = $target_directory . $this->image;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

Genero un mensaje de error de subida, que está vacío...
            $file_upload_error_messages = "";

A continuación paso a validar la imagen, y realizo las siguientes comprobaciones:
a. compruebo que sea una imagen real,y si no lo es doy un mensaje de error...
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
            } else {
                $file_upload_error_messages .= "<div>El archivo no es una imagen.</div>";
            }

b. compruebo que sea de una extensión que permito; defino yo misma qué extensiones permito dentro del array $allowed_file_types ...
            $allowed_file_types = array("jpg", "jpeg", "png", "gif");
            if (!in_array($file_type, $allowed_file_types)) {
                $file_upload_error_messages .= "<div>Sólo extensiones JPG, JPEG, PNG, GIF permitidas.</div>";
            }

c. compruebo que no exista ya el archivo ...
            if (file_exists($target_file)) {
                $file_upload_error_messages .= "<div>La imagen ya existe.</div>";
            }

d. compruebo que no sea mayor que 1 MB de tamaño (lo que no he decidido yo por mí misma sino me lo dio el propio tutorial) ...
            if ($_FILES['image']['size'] > (1024000)) {
                $file_upload_error_messages .= "<div>La imagen debe ocupar menos de 1MB.</div>";
            }

e. miro si existe la carpeta 'uploads' y si no la creo
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0777, true);
            }

f. Con todo anterior ya decido subirla. Si es válida la subo al servidor; esto lo miro según si $file_upload_error_messages está vacío.
En el primer if está vacío así que la subo, y si no puedo doy un mensaje de error...
            if (empty($file_upload_error_messages)) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                } else {
                    $result_message .= "<div class='alert alert-danger'>";
                    $result_message .= "<div>Imposible subir imagen.</div>";
                    $result_message .= "</div>";
                }
... pero si no está vacío hay errores, así que hay que enseñarlos; concateno $file_upload_error_messages a mi variable $result_message
            } else {
                $result_message .= "<div class='alert alert-danger'>";
                $result_message .= "{$file_upload_error_messages}";
                $result_message .= "</div>";
            }
        }

Y por último se hace return, lo que devuelve la variable $result_message que contiene el mensaje generado durante todo este proceso.

## 3. 
En el código original el script permite incluir una imagen del producto, pero no actualizarla.
Para poder actualizarla lo primero que hago es ir al archivo update_product.php. En la línea 36 (y siguiendo el ejemplo de la creación) añado:
$image = !empty($_FILES["image"]["name"]) ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"]) : "";
$product->image = $image;

Como es apropiado realizar todas las comprobaciones que he mencionado en el apartado anterior y debo llamar al método uploadPhoto(), añado también lo siguiente:

> if ($product->update()) {
>    echo $product->uploadPhoto();
>    [...]

A continuación lo que debo hacer es añadir una fila más al formulario, para poder actualizar la imagen. Esto lo creo a partir de la nueva línea 99 con el siguiente fragmento:

    <tr>
        <td>Foto</td>
        <td><input type='file' name='image' value='<?php echo $product->image; ?>' class='form-control' /></td>
    </tr>

Y con estos cambios, ya puedo actualizar la foto.