<?php
/**
 * Modelo: todo el acceso a datos de clientes, vía PDO. Nada de HTML acá.
 */

/**
 * Trae los clientes filtrados y ordenados según los parámetros recibidos
 * (los mismos nombres que llegan por $_GET desde el formulario de filtros).
 */
function obtenerClientes(PDO $pdo, array $parametrosGet): array
{
    // Whitelist: nunca se interpola directo un nombre de columna que venga del cliente.
    $columnas = [
        'codCliente'           => 'c.codCliente',
        'razonSocial'          => 'c.razonSocial',
        'cuit'                 => 'c.cuit',
        'condicionIva'         => 'i.nombre',
        'fechaInicio'          => 'c.fechaInicio',
        'saldoCuentaCorriente' => 'c.saldoCuentaCorriente',
        'ultimoBalance'        => 'c.ultimoBalance',
    ];

    $orden = $parametrosGet['orden'] ?? 'codCliente';
    if (!array_key_exists($orden, $columnas)) {
        $orden = 'codCliente';
    }

    $direccion = strtolower($parametrosGet['direccion'] ?? 'asc');
    if (!in_array($direccion, ['asc', 'desc'], true)) {
        $direccion = 'asc';
    }

    $condiciones = [];
    $parametros = [];

    // Filtros de texto: LIKE sobre cada columna, sólo si llegó un valor
    $filtrosTexto = ['codCliente', 'razonSocial', 'cuit', 'fechaInicio', 'saldoCuentaCorriente', 'ultimoBalance'];
    foreach ($filtrosTexto as $campo) {
        $valor = trim($parametrosGet['filtro_' . $campo] ?? '');
        if ($valor !== '') {
            $condiciones[] = $columnas[$campo] . ' LIKE :filtro_' . $campo;
            $parametros['filtro_' . $campo] = '%' . $valor . '%';
        }
    }

    // Filtro de condición IVA: viene como id desde un <select>, no como texto
    $filtroCondicionIva = $parametrosGet['filtro_condicionIva'] ?? '';
    if ($filtroCondicionIva !== '') {
        $condiciones[] = 'c.condicionIva_id = :filtro_condicionIva';
        $parametros['filtro_condicionIva'] = $filtroCondicionIva;
    }

    $where = $condiciones ? 'WHERE ' . implode(' AND ', $condiciones) : '';

    $sql = "
        SELECT c.id, c.codCliente, c.razonSocial, c.cuit,
               c.condicionIva_id, i.nombre AS condicionIva,
               c.fechaInicio, c.saldoCuentaCorriente, c.ultimoBalance
        FROM clientes c
        JOIN condiciones_iva i ON c.condicionIva_id = i.id
        {$where}
        ORDER BY {$columnas[$orden]} {$direccion}
    ";

    $consulta = $pdo->prepare($sql);
    $consulta->execute($parametros);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Da de alta un cliente nuevo. $archivo es el contenido binario del
 * adjunto (o null si no se subió ninguno). Devuelve ['exito' => bool, 'mensaje' => string opcional].
 */
function crearCliente(PDO $pdo, array $datos, ?string $archivo = null): array
{
    try {
        $sql = '
            INSERT INTO clientes (codCliente, razonSocial, cuit, condicionIva_id, fechaInicio, saldoCuentaCorriente, ultimoBalance, archivo)
            VALUES (:codCliente, :razonSocial, :cuit, :condicionIva_id, :fechaInicio, :saldoCuentaCorriente, :ultimoBalance, :archivo)
        ';

        $consulta = $pdo->prepare($sql);
        $consulta->bindValue(':codCliente', $datos['codCliente'] ?? '');
        $consulta->bindValue(':razonSocial', $datos['razonSocial'] ?? '');
        $consulta->bindValue(':cuit', $datos['cuit'] ?? '');
        $consulta->bindValue(':condicionIva_id', $datos['condicionIva_id'] ?? null);
        $consulta->bindValue(':fechaInicio', $datos['fechaInicio'] ?? null);
        $consulta->bindValue(':saldoCuentaCorriente', $datos['saldoCuentaCorriente'] ?? 0);
        $consulta->bindValue(':ultimoBalance', $datos['ultimoBalance'] ?? 0);
        $consulta->bindValue(':archivo', $archivo, PDO::PARAM_LOB);
        $consulta->execute();

        return ['exito' => true];
    } catch (PDOException $e) {
        registrarError('crearCliente: ' . $e->getMessage());
        return ['exito' => false, 'mensaje' => 'No se pudo crear el cliente.'];
    }
}

/**
 * Modifica un cliente existente, identificado por $id. Si $archivo es
 * null, se mantiene el adjunto que ya tenía (no se borra); si llega un
 * valor, lo reemplaza.
 */
function actualizarCliente(PDO $pdo, int $id, array $datos, ?string $archivo = null): array
{
    try {
        $sql = '
            UPDATE clientes SET
                codCliente = :codCliente,
                razonSocial = :razonSocial,
                cuit = :cuit,
                condicionIva_id = :condicionIva_id,
                fechaInicio = :fechaInicio,
                saldoCuentaCorriente = :saldoCuentaCorriente,
                ultimoBalance = :ultimoBalance' . ($archivo !== null ? ', archivo = :archivo' : '') . '
            WHERE id = :id
        ';

        $consulta = $pdo->prepare($sql);
        $consulta->bindValue(':codCliente', $datos['codCliente'] ?? '');
        $consulta->bindValue(':razonSocial', $datos['razonSocial'] ?? '');
        $consulta->bindValue(':cuit', $datos['cuit'] ?? '');
        $consulta->bindValue(':condicionIva_id', $datos['condicionIva_id'] ?? null);
        $consulta->bindValue(':fechaInicio', $datos['fechaInicio'] ?? null);
        $consulta->bindValue(':saldoCuentaCorriente', $datos['saldoCuentaCorriente'] ?? 0);
        $consulta->bindValue(':ultimoBalance', $datos['ultimoBalance'] ?? 0);
        if ($archivo !== null) {
            $consulta->bindValue(':archivo', $archivo, PDO::PARAM_LOB);
        }
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return ['exito' => true];
    } catch (PDOException $e) {
        registrarError('actualizarCliente: ' . $e->getMessage());
        return ['exito' => false, 'mensaje' => 'No se pudo modificar el cliente.'];
    }
}

/** Elimina un cliente por id. */
function eliminarCliente(PDO $pdo, int $id): array
{
    try {
        $consulta = $pdo->prepare('DELETE FROM clientes WHERE id = :id');
        $consulta->execute(['id' => $id]);

        return ['exito' => true];
    } catch (PDOException $e) {
        registrarError('eliminarCliente: ' . $e->getMessage());
        return ['exito' => false, 'mensaje' => 'No se pudo eliminar el cliente.'];
    }
}

/**
 * Trae el archivo binario adjunto a un cliente. No hay columna con el tipo
 * MIME guardado, así que se detecta leyendo la firma de los bytes.
 * Devuelve null si el cliente no existe o no tiene archivo cargado.
 */
function obtenerArchivoCliente(PDO $pdo, int $id): ?array
{
    $consulta = $pdo->prepare('SELECT archivo FROM clientes WHERE id = :id');
    $consulta->execute(['id' => $id]);
    $fila = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$fila || $fila['archivo'] === null) {
        return null;
    }

    $datos = $fila['archivo'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $tipo = $finfo->buffer($datos) ?: 'application/octet-stream';

    return ['datos' => $datos, 'tipo' => $tipo];
}
