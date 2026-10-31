<?php // Template: el HTML de una sola fila, en base a $cliente. Sin SQL, sin lógica. ?>
<tr>
    <td><?= htmlspecialchars($cliente['codCliente']) ?></td>
    <td><?= htmlspecialchars($cliente['razonSocial']) ?></td>
    <td><?= htmlspecialchars($cliente['cuit']) ?></td>
    <td><?= htmlspecialchars($cliente['condicionIva']) ?></td>
    <td><?= htmlspecialchars($cliente['fechaInicio']) ?></td>
    <td>$ <?= htmlspecialchars($cliente['saldoCuentaCorriente']) ?></td>
    <td>$ <?= htmlspecialchars($cliente['ultimoBalance']) ?></td>
    <td class="pdf">
        <button type="button" class="btn-abrir-doc" data-id="<?= $cliente['id'] ?>">Abrir</button>
    </td>
    <td class="acciones">
        <button
            type="button"
            class="btn-editar"
            title="Editar"
            data-id="<?= $cliente['id'] ?>"
            data-codcliente="<?= htmlspecialchars($cliente['codCliente']) ?>"
            data-razonsocial="<?= htmlspecialchars($cliente['razonSocial']) ?>"
            data-cuit="<?= htmlspecialchars($cliente['cuit']) ?>"
            data-condicioniva-id="<?= $cliente['condicionIva_id'] ?>"
            data-fechainicio="<?= $cliente['fechaInicio'] ?>"
            data-saldo="<?= $cliente['saldoCuentaCorriente'] ?>"
            data-balance="<?= $cliente['ultimoBalance'] ?>"
        >✏️</button>
        <button type="button" class="btn-eliminar" title="Eliminar" data-id="<?= $cliente['id'] ?>">🗑️</button>
    </td>
</tr>
