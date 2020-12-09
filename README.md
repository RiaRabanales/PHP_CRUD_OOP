# Repositorio:


## 1.
Para que en este código figure el símbolo '$' a la izquierda del precio (en el listado de productos y en el detalle) simplemente he tenido que añadir el símbolo apropiado al string que corresponde.
    1a. En el archivo index.php no he tenido que hacer cambios, sino que he podido trabajar directamente con read_template.php, ya que el primero incluye al segundo:
        En read_template.php, por tanto, he añadido, en la línea 39 el símbolo de dólar de la siguiente manera:
           echo "<td>$ {$price}</td>";
    1b. En el archivo read_one.php he hecho el mismo tipo de cambio en la línea 41:
            echo "<td>\$ {$product->price}</td>";
   

## 2.
El código que define cómo se guarda un producto está en el archivo create_product.php, pero los criterios que definen cómo se guarda una imagen están en la función uploadPhoto() en el archivo objects/product.php.
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
            a. compruebo que sea una imagen real...
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                Como en este caso compruebo el tipo de archivo, no hago nada porque efectivamente es una imagen. De lo contrario doy un mensaje de error.
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
            if (empty($file_upload_error_messages)) {
                Aquí está vacío así que la subo, y si no puedo doy un mensaje de error:
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                } else {
                    $result_message .= "<div class='alert alert-danger'>";
                    $result_message .= "<div>Imposible subir imagen.</div>";
                    $result_message .= "</div>";
                }
            } else {
                Si no está vacío hay errores, así que hay que enseñarlos; concateno $file_upload_error_messages a mi variable $result_message
                $result_message .= "<div class='alert alert-danger'>";
                $result_message .= "{$file_upload_error_messages}";
                $result_message .= "</div>";
            }
        }

        Y por último se hace return, lo que devuelve la variable $result_message que contiene el mensaje generado durante todo este proceso.

## 3.