<?php
/**
 * Vista: cierra la sesión del usuario (botón "Cerrar sesión") y vuelve al login.
 */
require 'middlewareSesion.php';

$_SESSION = [];
session_destroy();

header('Location: login.html');
exit;
