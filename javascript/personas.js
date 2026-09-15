const personas = [];

const contenedor = document.getElementById('contenedorPersonas');

document.getElementById('btnCrear').addEventListener('click', function () {
    const nombre = document.getElementById('nombre').value;
    const apellido = document.getElementById('apellido').value;
    const fechaNacimiento = document.getElementById('fechaNacimiento').value;

    if (nombre === '' || apellido === '' || fechaNacimiento === '') {
        alert('Completá los tres campos antes de crear la persona.');
        return;
    }

    const persona = {
        nombre: nombre,
        apellido: apellido,
        fechaNacimiento: fechaNacimiento
    };
    personas.push(persona);

    const tarjeta = document.createElement('div');
    tarjeta.className = 'tarjeta';
    tarjeta.textContent = persona.nombre + ' ' + persona.apellido + ' - Nació el ' + persona.fechaNacimiento;

    contenedor.appendChild(tarjeta);

    document.getElementById('formPersona').reset();
});

document.getElementById('btnListar').addEventListener('click', function () {
    contenedor.classList.remove('oculto');
});

document.getElementById('btnOcultar').addEventListener('click', function () {
    contenedor.classList.add('oculto');
});
