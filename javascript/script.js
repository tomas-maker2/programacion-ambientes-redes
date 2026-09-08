document.getElementById('verClave').addEventListener('click', function () {
    var campo = document.getElementById('clave');
    if (campo.type === 'password') {
        campo.type = 'text';
        this.textContent = 'Ocultar';
    } else {
        campo.type = 'password';
        this.textContent = 'Mostrar';
    }
});

document.querySelector('form').addEventListener('submit', function (evento) {
    evento.preventDefault();

    var datos = new FormData(evento.target);
    var lista = document.createElement('ul');

    for (var par of datos.entries()) {
        var item = document.createElement('li');
        item.textContent = par[0] + ': ' + par[1];
        lista.appendChild(item);
    }

    var resultado = document.getElementById('resultado');
    resultado.innerHTML = '<h2>Se hubiera enviado esto:</h2>';
    resultado.appendChild(lista);
});
