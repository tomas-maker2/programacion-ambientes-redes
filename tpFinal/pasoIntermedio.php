<?php
/**
 * Vista: paso intermedio, entre el login y el abm. Protegido por sesión.
 * Sólo muestra los datos de la sesión recién abierta; no toca el contador
 * de sesiones, así que recargar esta página no lo hace subir de nuevo.
 */
require 'middlewareSesion.php';
requerirSesion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesión iniciada - TP Final</title>
    <link rel="stylesheet" href="sesion.css">
</head>
<body>

    <div class="caja-sesion">
        <h1>Sesión iniciada</h1>
        <p class="subtitulo">Bienvenido/a, <?= htmlspecialchars($_SESSION['nombres']) ?> <?= htmlspecialchars($_SESSION['apellido']) ?></p>

        <div class="datos-sesion">
            <p><strong>GUID de sesión:</strong> <?= htmlspecialchars($_SESSION['guidSesion']) ?></p>
            <p><strong>Login:</strong> <?= htmlspecialchars($_SESSION['login']) ?></p>
            <p><strong>Password:</strong> <?= htmlspecialchars($_SESSION['password']) ?></p>
        </div>

        <a class="boton" href="clientes.php">Ingresar al sistema</a>

        <form method="post" action="eliminarSesion.php">
            <button type="submit" class="secundario">Cerrar sesión</button>
        </form>
    </div>

</body>
</html>
