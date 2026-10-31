<?php
/**
 * Vista: trae el archivo binario (PDF o imagen) guardado para un cliente.
 * Técnica del apunte: el binario se lee de la base, se codifica en base64
 * (así no se confunde con texto/HTML al viajar) y se entrega envuelto en JSON.
 */
require 'middlewareSesion.php';
requerirSesion();
require 'config.php';
require 'ModeloCliente.php';

$pdo = obtenerConexion();
$id = (int) ($_GET['id'] ?? 0);

$archivo = obtenerArchivoCliente($pdo, $id);

header('Content-Type: application/json');

if ($archivo === null) {
    echo json_encode(['error' => 'Este cliente no tiene ningún archivo adjunto.']);
    exit;
}

$salida = [
    'tipo'        => $archivo['tipo'],
    'documentoPdf' => base64_encode($archivo['datos']),
];

echo json_encode($salida, JSON_INVALID_UTF8_SUBSTITUTE);
