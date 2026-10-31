<?php
/**
 * Plantilla de config.php. config.php está en .gitignore (no se sube a
 * GitHub) porque ahí van a vivir las credenciales reales de InfinityFree.
 * Para correr el proyecto: copiar este archivo como config.php y completar
 * los datos reales de conexión de cada entorno.
 */

/** Escribe un error (de conexión o de ejecución de una sentencia) en errores.log, con fecha y hora. */
function registrarError(string $mensaje): void
{
    $puntero = fopen(__DIR__ . '/errores.log', 'a');
    fwrite($puntero, date('Y-m-d H:i:s') . ' ' . $mensaje . "\n");
    fclose($puntero);
}

/**
 * Devuelve una conexión PDO a la base de datos, usando credenciales
 * distintas según dónde esté corriendo: localhost (XAMPP) o InfinityFree.
 */
function obtenerConexion(): PDO
{
    $esLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']);

    if ($esLocal) {
        $host     = 'localhost';
        $base     = 'tp_final';
        $usuario  = 'root';
        $clave    = '';
    } else {
        // Completar con los datos reales de MySQL que da InfinityFree
        // (Panel → MySQL Databases) antes de subir esto.
        $host     = 'TODO_HOST_INFINITYFREE';
        $base     = 'TODO_NOMBRE_BASE';
        $usuario  = 'TODO_USUARIO';
        $clave    = 'TODO_CLAVE';
    }

    try {
        return new PDO(
            "mysql:host={$host};dbname={$base};charset=utf8mb4",
            $usuario,
            $clave,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        registrarError('Error de conexión: ' . $e->getMessage());
        throw $e;
    }
}
