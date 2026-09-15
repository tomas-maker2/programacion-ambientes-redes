const contenedor = document.getElementById('contenedor');

document.getElementById('btnCrear').addEventListener('click', function () {
    const numero = contenedor.children.length;

    const elemento = document.createElement('div');
    elemento.className = 'elemento';
    elemento.textContent = 'Elemento creado ' + numero;

    contenedor.appendChild(elemento);
});

document.getElementById('btnLimpiar').addEventListener('click', function () {
    contenedor.innerHTML = '';
});

document.getElementById('btnInfo').addEventListener('click', function () {
    alert('Hay ' + contenedor.children.length + ' elementos creados.');
});
