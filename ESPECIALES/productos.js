import productos from './productos.json' with { type: 'json' };
import categorias from './categorias.json' with { type: 'json' };

const cuerpoTabla = document.getElementById('cuerpoTabla');
const selectCategoria = document.getElementById('categoria');
const modal = document.getElementById('modal');
const formulario = document.getElementById('formProducto');

function crearFila(producto) {
    const fila = document.createElement('tr');

    const valores = [producto.id, producto.nombre, producto.categoria, '$ ' + producto.precio];

    valores.forEach(function (valor) {
        const celda = document.createElement('td');
        celda.textContent = valor;
        fila.appendChild(celda);
    });

    cuerpoTabla.appendChild(fila);
}

productos.forEach(function (producto) {
    crearFila(producto);
});

categorias.forEach(function (categoria) {
    const opcion = document.createElement('option');
    opcion.value = categoria.nombre;
    opcion.textContent = categoria.nombre;
    selectCategoria.appendChild(opcion);
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
        id: productos.length + 1,
        nombre: document.getElementById('nombre').value,
        categoria: selectCategoria.value,
        precio: document.getElementById('precio').value
    };

    productos.push(nuevo);
    crearFila(nuevo);

    formulario.reset();
    modal.classList.add('oculto');
});
