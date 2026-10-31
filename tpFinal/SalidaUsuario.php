<?php
require 'middlewareSesion.php';
requerirSesion();
require 'config.php';

$pdo = obtenerConexion();

// No se trae la columna contraseña: nunca debe viajar en una respuesta JSON.
$consulta = $pdo->query('SELECT idUsuario, login, apellido, nombres, cantidadSesiones FROM usuarios ORDER BY idUsuario');

$usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($usuarios);
