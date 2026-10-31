<?php
/**
 * Vista: punto de entrada del AJAX. No hace SQL (eso es el Modelo) ni
 * decide el HTML de cada fila (eso es el Template) — sólo conecta las
 * dos cosas y recorre el resultado.
 */
require 'middlewareSesion.php';
requerirSesion();
require 'config.php';
require 'ModeloCliente.php';

$pdo = obtenerConexion();
$clientes = obtenerClientes($pdo, $_GET);

if (!$clientes) {
    echo '<tr><td colspan="7">No se encontraron clientes con esos filtros.</td></tr>';
} else {
    foreach ($clientes as $cliente) {
        require 'TemplateFilaCliente.php';
    }
}
