const cuerpoTabla = document.getElementById('cuerpoTabla');
const selectCondicionIvaModal = document.getElementById('condicionIva');
const selectFiltroCondicionIva = document.querySelector('[data-filtro="condicionIva"]');
const selectOrdenColumna = document.getElementById('ordenColumna');
const selectOrdenDireccion = document.getElementById('ordenDireccion');
const modal = document.getElementById('modal');
const tituloModal = document.getElementById('tituloModal');
const formulario = document.getElementById('formCliente');
const modalEliminar = document.getElementById('modalEliminar');
const mensajeFormulario = document.getElementById('mensajeFormulario');
const mensajeEliminar = document.getElementById('mensajeEliminar');
const modalDocumento = document.getElementById('modalDocumento');
const iframeDocumento = document.getElementById('iframeDocumento');
const mensajeDocumento = document.getElementById('mensajeDocumento');

// Si tiene valor, el modal está editando ese id; si es null, está dando de alta.
let idEnEdicion = null;

// Id del cliente que se va a borrar si se confirma en el modal de eliminación.
let idAEliminar = null;

// Estado actual de orden y filtros. Cada cambio dispara una nueva consulta a la Vista.
const estado = {
    orden: 'codCliente',
    direccion: 'asc',
    filtros: {
        codCliente: '',
        razonSocial: '',
        cuit: '',
        condicionIva: '',
        fechaInicio: '',
        saldoCuentaCorriente: '',
        ultimoBalance: ''
    }
};

// Pide a la Vista la tabla ya armada en HTML (Modelo -> Vista -> Template)
// y sólo la inserta: acá no se arma ninguna fila a mano.
function cargarTabla() {
    const params = new URLSearchParams();
    params.set('orden', estado.orden);
    params.set('direccion', estado.direccion);

    Object.keys(estado.filtros).forEach(function (campo) {
        if (estado.filtros[campo] !== '') {
            params.set('filtro_' + campo, estado.filtros[campo]);
        }
    });

    fetch('VistaCliente.php?' + params.toString())
        .then(function (respuesta) {
            return respuesta.text();
        })
        .then(function (html) {
            cuerpoTabla.innerHTML = html;
        });
}

// Orden: un select para la columna y otro para la dirección, al lado de "Agregar cliente"
selectOrdenColumna.addEventListener('change', function () {
    estado.orden = selectOrdenColumna.value;
    cargarTabla();
});

selectOrdenDireccion.addEventListener('change', function () {
    estado.direccion = selectOrdenDireccion.value;
    cargarTabla();
});

// Filtros de texto: se dispara una consulta nueva en cada tecla
document.querySelectorAll('input[data-filtro]').forEach(function (input) {
    input.addEventListener('input', function () {
        estado.filtros[input.dataset.filtro] = input.value;
        cargarTabla();
    });
});

// Filtro de condición IVA: se dispara al elegir una opción
selectFiltroCondicionIva.addEventListener('change', function () {
    estado.filtros.condicionIva = selectFiltroCondicionIva.value;
    cargarTabla();
});

function agregarOpcion(select, valor, texto) {
    const opcion = document.createElement('option');
    opcion.value = valor;
    opcion.textContent = texto;
    select.appendChild(opcion);
}

// Las condiciones de IVA salen de la base de datos, y alimentan dos selects:
// el filtro de la tabla y el select del modal. Los dos usan value = id.
fetch('SalidaCondicionIva.php')
    .then(function (respuesta) {
        return respuesta.json();
    })
    .then(function (condicionesIva) {
        condicionesIva.forEach(function (condicion) {
            agregarOpcion(selectFiltroCondicionIva, condicion.id, condicion.nombre);
            agregarOpcion(selectCondicionIvaModal, condicion.id, condicion.nombre);
        });
    });

cargarTabla();

// Validación en vivo: se revisa cada campo mientras se escribe, no recién al guardar
const campos = formulario.querySelectorAll('input, select');

campos.forEach(function (campo) {
    campo.addEventListener('input', function () {
        validarCampo(campo);
    });
});

function validarCampo(campo) {
    const mensaje = document.getElementById('mensaje-' + campo.id);

    if (campo.checkValidity()) {
        campo.classList.remove('invalido');
        mensaje.textContent = '';
    } else {
        campo.classList.add('invalido');
        mensaje.textContent = campo.validationMessage;
    }
}

// Abrir el modal para dar de alta: lo deja en blanco y en modo "crear"
document.getElementById('btnAgregar').addEventListener('click', function () {
    idEnEdicion = null;
    tituloModal.textContent = 'Agregar cliente';
    formulario.reset();
    document.getElementById('mensaje-archivo').textContent = '';
    mensajeFormulario.textContent = '';
    mensajeFormulario.classList.add('oculto');
    modal.classList.remove('oculto');
});

document.getElementById('btnCancelar').addEventListener('click', function () {
    modal.classList.add('oculto');
});

// Delegación de eventos: los botones de cada fila los crea el Template del
// servidor y se reemplazan en cada cargarTabla(), así que el listener va
// en el tbody (que no se destruye) y no en cada botón.
cuerpoTabla.addEventListener('click', function (evento) {
    const botonEditar = evento.target.closest('.btn-editar');
    if (botonEditar) {
        abrirEdicion(botonEditar);
        return;
    }

    const botonEliminar = evento.target.closest('.btn-eliminar');
    if (botonEliminar) {
        idAEliminar = botonEliminar.dataset.id;
        modalEliminar.classList.remove('oculto');
        return;
    }

    const botonAbrirDoc = evento.target.closest('.btn-abrir-doc');
    if (botonAbrirDoc) {
        abrirDocumento(botonAbrirDoc.dataset.id);
    }
});

// Trae el archivo con fetch (API fetch, siempre). El servidor lo manda en
// JSON con el binario codificado en base64; se arma el iframe con un src
// data: URI -- un iframe no se llena con innerHTML, su contenido es un
// documento aparte que carga por src.
function abrirDocumento(id) {
    mensajeDocumento.textContent = '';
    mensajeDocumento.classList.add('oculto');
    iframeDocumento.src = 'about:blank';

    modalDocumento.classList.remove('oculto');

    fetch('traeDoc.php?id=' + id)
        .then(function (respuesta) {
            return respuesta.text();
        })
        .then(function (texto) {
            const datos = JSON.parse(texto);

            if (datos.error) {
                throw new Error(datos.error);
            }

            iframeDocumento.src = 'data:' + datos.tipo + ';base64,' + datos.documentoPdf;
        })
        .catch(function (error) {
            mensajeDocumento.textContent = error.message;
            mensajeDocumento.classList.remove('oculto');
        });
}

document.getElementById('btnCerrarDocumento').addEventListener('click', function () {
    modalDocumento.classList.add('oculto');
    iframeDocumento.src = 'about:blank';
});

// Abre el mismo modal de "Agregar cliente", pero precargado y en modo edición
function abrirEdicion(boton) {
    idEnEdicion = boton.dataset.id;
    tituloModal.textContent = 'Editar cliente';
    mensajeFormulario.textContent = '';
    mensajeFormulario.classList.add('oculto');

    document.getElementById('codCliente').value = boton.dataset.codcliente;
    document.getElementById('razonSocial').value = boton.dataset.razonsocial;
    document.getElementById('cuit').value = boton.dataset.cuit;
    selectCondicionIvaModal.value = boton.dataset.condicionivaId;
    document.getElementById('fechaInicio').value = boton.dataset.fechainicio;
    document.getElementById('saldoCuentaCorriente').value = boton.dataset.saldo;
    document.getElementById('ultimoBalance').value = boton.dataset.balance;

    document.getElementById('archivo').value = '';
    document.getElementById('mensaje-archivo').textContent = 'Dejá vacío para mantener el archivo actual.';

    campos.forEach(function (campo) {
        validarCampo(campo);
    });

    modal.classList.remove('oculto');
}

formulario.addEventListener('submit', function (evento) {
    evento.preventDefault();

    campos.forEach(function (campo) {
        validarCampo(campo);
    });

    if (!formulario.checkValidity()) {
        return;
    }

    const datos = new FormData(formulario);
    const destino = idEnEdicion ? 'modificacion.php' : 'alta.php';

    if (idEnEdicion) {
        datos.set('id', idEnEdicion);
    }

    fetch(destino, {
        method: 'POST',
        body: datos
    })
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (json) {
            if (!json.exito) {
                mensajeFormulario.textContent = json.mensaje || 'No se pudo guardar el cliente.';
                mensajeFormulario.classList.remove('oculto', 'exito');
                return;
            }

            mensajeFormulario.textContent = idEnEdicion
                ? 'Modificación hecha correctamente.'
                : 'Alta hecha correctamente.';
            mensajeFormulario.classList.remove('oculto');
            mensajeFormulario.classList.add('exito');

            setTimeout(function () {
                formulario.reset();
                modal.classList.add('oculto');
                mensajeFormulario.classList.remove('exito');
                idEnEdicion = null;
                cargarTabla();
            }, 1200);
        });
});

// Modal de confirmación de borrado
document.getElementById('btnCancelarEliminar').addEventListener('click', function () {
    idAEliminar = null;
    mensajeEliminar.textContent = '';
    mensajeEliminar.classList.add('oculto');
    modalEliminar.classList.add('oculto');
});

document.getElementById('btnConfirmarEliminar').addEventListener('click', function () {
    const datos = new FormData();
    datos.set('id', idAEliminar);

    fetch('baja.php', {
        method: 'POST',
        body: datos
    })
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (json) {
            if (!json.exito) {
                mensajeEliminar.textContent = json.mensaje || 'No se pudo eliminar el cliente.';
                mensajeEliminar.classList.remove('oculto', 'exito');
                return;
            }

            mensajeEliminar.textContent = 'Baja hecha correctamente.';
            mensajeEliminar.classList.remove('oculto');
            mensajeEliminar.classList.add('exito');

            setTimeout(function () {
                idAEliminar = null;
                mensajeEliminar.textContent = '';
                mensajeEliminar.classList.remove('exito');
                mensajeEliminar.classList.add('oculto');
                modalEliminar.classList.add('oculto');
                cargarTabla();
            }, 1200);
        });
});
