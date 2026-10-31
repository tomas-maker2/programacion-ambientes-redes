<?php
/**
 * Vista: baja de un cliente. Llama al Modelo, responde JSON.
 */
require 'middlewareSesion.php';
requerirSesion();
require 'config.php';
require 'ModeloCliente.php';

$pdo = obtenerConexion();
$id = (int) ($_POST['id'] ?? 0);
$resultado = eliminarCliente($pdo, $id);

header('Content-Type: application/json');
echo json_encode($resultado);
