<?php
// Las claves que ya se muestran arriba en tablas, para no repetirlas abajo
$mostradas = [];

/** Arma una tabla con las claves de $_SERVER indicadas, escapando cada valor. */
function tablaServidor(array $claves, array &$mostradas): void
{
    echo '<table><tbody>';
    foreach ($claves as $clave) {
        $mostradas[] = $clave;
        $valor = $_SERVER[$clave] ?? '(no definida)';
        echo '<tr><th>' . htmlspecialchars($clave) . '</th><td>' . htmlspecialchars($valor) . '</td></tr>';
    }
    echo '</tbody></table>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Variables de servidor - Clase PHP</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <h1>Variables de servidor ($_SERVER)</h1>
    <p>PHP arma <code>$_SERVER</code> con datos que manda el navegador y datos que ya tiene Apache.</p>

    <h2>Variables del servidor</h2>
    <?php tablaServidor(['SERVER_PORT', 'SERVER_ADDR', 'SERVER_NAME', 'HTTP_HOST', 'DOCUMENT_ROOT'], $mostradas); ?>

    <h2>Variables del cliente</h2>
    <?php tablaServidor(['REMOTE_ADDR', 'REMOTE_PORT', 'HTTP_USER_AGENT', 'HTTP_ACCEPT_LANGUAGE'], $mostradas); ?>

    <h2>Variables del requerimiento (HTTP)</h2>
    <?php tablaServidor(['REQUEST_METHOD', 'REQUEST_URI', 'QUERY_STRING', 'SERVER_PROTOCOL', 'HTTP_ACCEPT'], $mostradas); ?>

    <h2>Todas las demás</h2>
    <div class="todas">
        <?php foreach ($_SERVER as $clave => $valor): ?>
            <?php if (in_array($clave, $mostradas)) continue; ?>
            <?php if (is_array($valor)) $valor = implode(', ', $valor); ?>
            <p><b><?php echo htmlspecialchars($clave); ?>:</b> <?php echo htmlspecialchars($valor); ?></p>
        <?php endforeach; ?>
    </div>

</body>
</html>
