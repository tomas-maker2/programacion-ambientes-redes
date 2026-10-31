<?php
/**
 * Middleware de sesión. Todo .php protegido lo requiere al principio.
 * Sólo arranca la sesión; la verificación de que haya una sesión activa
 * la hace requerirSesion(), para que IngresoSistema.php y eliminarSesion.php
 * puedan arrancar la sesión sin verse obligados a tener una ya abierta.
 */
session_start();

/** Si no hay una sesión de usuario activa, corta todo y manda al login. */
function requerirSesion(): void
{
    if (empty($_SESSION['idUsuario'])) {
        header('Location: login.html');
        exit;
    }
}
