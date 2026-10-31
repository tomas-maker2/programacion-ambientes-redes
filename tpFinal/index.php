<?php
/**
 * Puerta de entrada de la aplicación. Evalúa la sesión en su primera
 * línea: si ya hay una activa, pasa directo al abm; si no, al login.
 */
require 'middlewareSesion.php';

if (!empty($_SESSION['idUsuario'])) {
    header('Location: clientes.php');
} else {
    header('Location: login.html');
}
exit;
