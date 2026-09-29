<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>include() - Clase PHP</title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; max-width: 700px; margin: 0 auto; padding: 30px 20px; }
        h1 { color: #2e7d32; }
        h2 { margin-top: 40px; }
        table { border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
        th { background-color: #2e7d32; color: #fff; }
    </style>
</head>
<body>

    <h1>La función include()</h1>
    <p>
        <code>include()</code> ubica y ejecuta acá mismo el código de otro archivo PHP,
        en este caso <code>asignaciones.php</code>. Las variables que ese archivo declara
        recién existen <strong>a partir de la línea donde se hace el include</strong>, no antes.
    </p>

    <h2>Las variables son:</h2>
    <p>
        Curso: <?php echo $curso; ?><br>
        Año: <?php echo $anio; ?>
    </p>
    <p>
        Todavía no se hizo el <code>include()</code>, así que <code>$curso</code> y <code>$anio</code>
        no existen: PHP tira un <em>Warning: Undefined variable</em> arriba de este párrafo.
    </p>

    <?php include 'asignaciones.php'; ?>

    <h2>Después del include():</h2>
    <p>
        Curso: <?php echo $curso; ?><br>
        Año: <?php echo $anio; ?>
    </p>
    <p>Ahora sí existen, porque ya se ejecutó <code>asignaciones.php</code>.</p>

    <h2>Variable asociativa: personas</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Nacimiento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($personas as $persona): ?>
                <tr>
                    <td><?php echo $persona['nombre']; ?></td>
                    <td><?php echo $persona['apellido']; ?></td>
                    <td><?php echo $persona['nacimiento']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
