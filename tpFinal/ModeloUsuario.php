<?php
/**
 * Modelo: todo el acceso a datos de usuarios, vía PDO. Nada de HTML acá.
 */

/**
 * Valida login y contraseña contra la tabla usuarios.
 * Devuelve los datos del usuario (sin la contraseña) o null si no coinciden.
 */
function validarCredenciales(PDO $pdo, string $login, string $password): ?array
{
    try {
        $consulta = $pdo->prepare('SELECT idUsuario, login, apellido, nombres, contraseña FROM usuarios WHERE login = :login');
        $consulta->execute(['login' => $login]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        registrarError('validarCredenciales: ' . $e->getMessage());
        return null;
    }

    if (!$usuario || !hash_equals($usuario['contraseña'], hash('sha256', $password))) {
        return null;
    }

    unset($usuario['contraseña']);
    return $usuario;
}

/** Suma 1 al contador de sesiones abiertas del usuario. */
function incrementarContadorSesiones(PDO $pdo, int $idUsuario): void
{
    try {
        $consulta = $pdo->prepare('UPDATE usuarios SET cantidadSesiones = cantidadSesiones + 1 WHERE idUsuario = :id');
        $consulta->execute(['id' => $idUsuario]);
    } catch (PDOException $e) {
        registrarError('incrementarContadorSesiones: ' . $e->getMessage());
    }
}
