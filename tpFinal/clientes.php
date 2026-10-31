<?php
/**
 * Vista: página principal del abm. Protegida por sesión -- sin sesión
 * válida, requerirSesion() manda directo al login.
 */
require 'middlewareSesion.php';
requerirSesion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - TP Final</title>
    <link rel="stylesheet" href="clientes.css">
</head>
<body>

    <div class="barra-usuario">
        <span>Conectado como <strong><?= htmlspecialchars($_SESSION['nombres']) ?> <?= htmlspecialchars($_SESSION['apellido']) ?></strong></span>
        <form method="post" action="eliminarSesion.php">
            <button type="submit" class="secundario">Cerrar sesión</button>
        </form>
    </div>

    <h1>Clientes</h1>
    <p>TP Final - Programación en Ambientes de Redes</p>

    <div class="barra-acciones">
        <button type="button" id="btnAgregar">Agregar cliente</button>

        <label for="ordenColumna">Ordenar por</label>
        <select id="ordenColumna">
            <option value="codCliente">Cod. Cliente</option>
            <option value="razonSocial">Razón Social</option>
            <option value="cuit">CUIT</option>
            <option value="condicionIva">Condición IVA</option>
            <option value="fechaInicio">Fecha de inicio</option>
            <option value="saldoCuentaCorriente">Saldo cta. cte.</option>
            <option value="ultimoBalance">Último balance</option>
        </select>

        <select id="ordenDireccion">
            <option value="asc">Ascendente</option>
            <option value="desc">Descendente</option>
        </select>
    </div>

    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>Cod. Cliente</th>
                    <th>Razón Social</th>
                    <th>CUIT</th>
                    <th>Condición IVA</th>
                    <th>Fecha de inicio</th>
                    <th>Saldo cta. cte.</th>
                    <th>Último balance</th>
                    <th>PDF</th>
                    <th>Acciones</th>
                </tr>
                <tr class="fila-filtros">
                    <th><input type="text" data-filtro="codCliente" placeholder="Filtrar..."></th>
                    <th><input type="text" data-filtro="razonSocial" placeholder="Filtrar..."></th>
                    <th><input type="text" data-filtro="cuit" placeholder="Filtrar..."></th>
                    <th>
                        <select data-filtro="condicionIva">
                            <option value="">Todas</option>
                        </select>
                    </th>
                    <th><input type="text" data-filtro="fechaInicio" placeholder="Filtrar..."></th>
                    <th><input type="text" data-filtro="saldoCuentaCorriente" placeholder="Filtrar..."></th>
                    <th><input type="text" data-filtro="ultimoBalance" placeholder="Filtrar..."></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="cuerpoTabla"></tbody>
        </table>
    </div>

    <div id="modal" class="capa-modal oculto">
        <form id="formCliente" enctype="multipart/form-data">
            <h2 id="tituloModal">Agregar cliente</h2>

            <div class="grilla-campos">
                <p class="campo">
                    <label for="codCliente">Cod. Cliente</label>
                    <input type="text" id="codCliente" name="codCliente" required>
                    <span class="mensaje" id="mensaje-codCliente"></span>
                </p>

                <p class="campo">
                    <label for="razonSocial">Razón Social</label>
                    <input type="text" id="razonSocial" name="razonSocial" required>
                    <span class="mensaje" id="mensaje-razonSocial"></span>
                </p>

                <p class="campo">
                    <label for="cuit">CUIT</label>
                    <input type="text" id="cuit" name="cuit" pattern="[0-9]{2}-[0-9]{8}-[0-9]{1}" title="Formato: 30-12345678-9" placeholder="30-12345678-9" required>
                    <span class="mensaje" id="mensaje-cuit"></span>
                </p>

                <p class="campo">
                    <label for="condicionIva">Condición IVA</label>
                    <select id="condicionIva" name="condicionIva_id" required></select>
                    <span class="mensaje" id="mensaje-condicionIva"></span>
                </p>

                <p class="campo">
                    <label for="fechaInicio">Fecha de inicio</label>
                    <input type="date" id="fechaInicio" name="fechaInicio" required>
                    <span class="mensaje" id="mensaje-fechaInicio"></span>
                </p>

                <p class="campo">
                    <label for="saldoCuentaCorriente">Saldo cuenta corriente</label>
                    <input type="number" id="saldoCuentaCorriente" name="saldoCuentaCorriente" step="0.01" required>
                    <span class="mensaje" id="mensaje-saldoCuentaCorriente"></span>
                </p>

                <p class="campo">
                    <label for="ultimoBalance">Último balance</label>
                    <input type="number" id="ultimoBalance" name="ultimoBalance" step="0.01" required>
                    <span class="mensaje" id="mensaje-ultimoBalance"></span>
                </p>

                <p class="campo">
                    <label for="archivo">Archivo (PDF o imagen)</label>
                    <input type="file" id="archivo" name="archivo" accept="application/pdf,image/*">
                    <span class="mensaje" id="mensaje-archivo"></span>
                </p>
            </div>

            <p class="mensaje-formulario oculto" id="mensajeFormulario"></p>

            <p class="botones">
                <button type="submit">Guardar</button>
                <button type="button" id="btnCancelar">Cancelar</button>
            </p>
        </form>
    </div>

    <div id="modalEliminar" class="capa-modal oculto">
        <div class="caja-confirmacion">
            <h2>Eliminar cliente</h2>
            <p>¿Seguro que querés eliminar este cliente? Esta acción no se puede deshacer.</p>
            <p class="mensaje-formulario oculto" id="mensajeEliminar"></p>
            <p class="botones">
                <button type="button" id="btnConfirmarEliminar">Eliminar</button>
                <button type="button" id="btnCancelarEliminar">Cancelar</button>
            </p>
        </div>
    </div>

    <div id="modalDocumento" class="capa-modal oculto">
        <div class="caja-documento">
            <div class="cabecera-documento">
                <h2>Archivo del cliente</h2>
                <button type="button" id="btnCerrarDocumento">Cerrar</button>
            </div>
            <p class="mensaje-formulario oculto" id="mensajeDocumento"></p>
            <iframe id="iframeDocumento" title="Archivo adjunto del cliente"></iframe>
        </div>
    </div>

    <script src="clientes.js"></script>

</body>
</html>
