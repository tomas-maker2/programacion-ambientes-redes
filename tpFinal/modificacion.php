<?php
/**
 * Vista: modificación de un cliente existente. Llama al Modelo, responde JSON.
 */
require 'middlewareSesion.php';
requerirSesion();
require 'config.php';
require 'ModeloCliente.php';

$pdo = obtenerConexion();
$id = (int) ($_POST['id'] ?? 0);

$archivo = null;
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    $archivo = file_get_contents($_FILES['archivo']['tmp_name']);
}

$resultado = actualizarCliente($pdo, $id, $_POST, $archivo);

header('Content-Type: application/json');
echo json_encode($resultado);
