import clientes from './clientes.json' with { type: 'json' };
import condicionesIva from './condicionesIva.json' with { type: 'json' };

const cuerpoTabla = document.getElementById('cuerpoTabla');
const selectCondicionIva = document.getElementById('condicionIva');
const modal = document.getElementById('modal');
const formulario = document.getElementById('formCliente');

function crearFila(cliente) {
    const fila = document.createElement('tr');

    const valores = [
        cliente.codCliente,
        cliente.razonSocial,
        cliente.cuit,
        cliente.condicionIva,
        cliente.fechaInicio,
        '$ ' + cliente.saldoCuentaCorriente,
        '$ ' + cliente.ultimoBalance
    ];

    valores.forEach(function (valor) {
        const celda = document.createElement('td');
        celda.textContent = valor;
        fila.appendChild(celda);
    });

    cuerpoTabla.appendChild(fila);
}

clientes.forEach(function (cliente) {
    crearFila(cliente);
});

condicionesIva.forEach(function (condicion) {
    const opcion = document.createElement('option');
    opcion.value = condicion.nombre;
    opcion.textContent = condicion.nombre;
    selectCondicionIva.appendChild(opcion);
});

document.getElementById('btnAgregar').addEventListener('click', function () {
    modal.classList.remove('oculto');
});

document.getElementById('btnCancelar').addEventListener('click', function () {
    modal.classList.add('oculto');
});

formulario.addEventListener('submit', function (evento) {
    evento.preventDefault();

    const nuevo = {
        codCliente: document.getElementById('codCliente').value,
        razonSocial: document.getElementById('razonSocial').value,
        cuit: document.getElementById('cuit').value,
        condicionIva: selectCondicionIva.value,
        fechaInicio: document.getElementById('fechaInicio').value,
        saldoCuentaCorriente: document.getElementById('saldoCuentaCorriente').value,
        ultimoBalance: document.getElementById('ultimoBalance').value
    };

    clientes.push(nuevo);
    crearFila(nuevo);

    formulario.reset();
    modal.classList.add('oculto');
});
