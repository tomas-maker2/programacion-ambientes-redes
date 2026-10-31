<?php
require 'middlewareSesion.php';
requerirSesion();
require 'config.php';

$pdo = obtenerConexion();

$consulta = $pdo->query('SELECT id, nombre FROM condiciones_iva ORDER BY id');

$condicionesIva = $consulta->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($condicionesIva);
