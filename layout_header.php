<!DOCTYPE html>
<html lang="en">
    <head>

        <!-- Aquí incluyo la cabecera de mi proyecto -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo $page_title; ?></title>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

        <!-- Mi custom CSS -->
        <link rel="stylesheet" href="libs/css/custom.css" />

    </head>
    <body>

        <!-- Inicio de container -->
        <div class="container">

            <?php
            // Cabecera:
            echo "<div class='page-header'>
                <h1>{$page_title}</h1>
            </div>";
            ?>
