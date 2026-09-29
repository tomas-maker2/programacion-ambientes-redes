<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info del servidor - Clase PHP</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <h1>Fecha, hora y entorno del servidor</h1>

    <p class="dato">
        <span>Fecha y hora del servidor:</span>
        <?php echo date('d/m/Y H:i:s'); ?>
    </p>

    <hr>

    <?php phpinfo(); ?>

</body>
</html>
