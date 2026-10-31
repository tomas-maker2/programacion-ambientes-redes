// Este formulario no usa fetch: es una navegación normal (login -> IngresoSistema.php
// -> paso intermedio -> abm), así que el único trabajo acá es mostrar el error de
// login cuando IngresoSistema.php nos manda de vuelta con ?error=1 en la URL.
const parametros = new URLSearchParams(window.location.search);

if (parametros.get('error') === '1') {
    document.getElementById('mensajeError').classList.remove('oculto');
}
