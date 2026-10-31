<?php
require 'middlewareSesion.php';
requerirSesion();
require 'config.php';

$pdo = obtenerConexion();

$consulta = $pdo->query('
    SELECT
        c.id,
        c.codCliente,
        c.razonSocial,
        c.cuit,
        i.nombre AS condicionIva,
        c.fechaInicio,
        c.saldoCuentaCorriente,
        c.ultimoBalance
    FROM clientes c
    JOIN condiciones_iva i ON c.condicionIva_id = i.id
    ORDER BY c.id
');

$clientes = $consulta->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($clientes);
