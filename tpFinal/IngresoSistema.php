<?php
/**
 * Vista: valida login/contraseña del formulario y abre la sesión.
 * No arranca con requerirSesion() porque acá todavía no hay sesión:
 * es este script el que la crea.
 */
require 'middlewareSesion.php';

// Si ya hay sesión activa (por ejemplo, un reload de este mismo script
// reenviando el POST), no se vuelve a autenticar ni a sumar el contador:
// se manda directo al paso intermedio con la sesión que ya existía.
if (!empty($_SESSION['idUsuario'])) {
    header('Location: pasoIntermedio.php');
    exit;
}

require 'config.php';
require 'ModeloUsuario.php';

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

$pdo = obtenerConexion();
$usuario = validarCredenciales($pdo, $login, $password);

if ($usuario === null) {
    header('Location: login.html?error=1');
    exit;
}

// Sesión nueva de verdad: se regenera el id para que no quede pisando una sesión anterior.
session_regenerate_id(true);

$_SESSION['idUsuario']   = $usuario['idUsuario'];
$_SESSION['login']       = $usuario['login'];
$_SESSION['apellido']    = $usuario['apellido'];
$_SESSION['nombres']     = $usuario['nombres'];
$_SESSION['password']    = $password;
$_SESSION['guidSesion']  = bin2hex(random_bytes(16));

// El contador sube acá, una sola vez por login real. Nada en el paso
// intermedio ni en el resto de la app vuelve a tocar esta columna, así
// que recargar esa pantalla no lo hace subir de nuevo.
incrementarContadorSesiones($pdo, $usuario['idUsuario']);

header('Location: pasoIntermedio.php');
exit;
