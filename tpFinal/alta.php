<?php
/**
 * Vista: alta de un cliente. Llama al Modelo, responde el resultado en JSON.
 */
require 'middlewareSesion.php';
requerirSesion();
require 'config.php';
require 'ModeloCliente.php';

$pdo = obtenerConexion();

$archivo = null;
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    $archivo = file_get_contents($_FILES['archivo']['tmp_name']);
}

$resultado = crearCliente($pdo, $_POST, $archivo);

header('Content-Type: application/json');
echo json_encode($resultado);
