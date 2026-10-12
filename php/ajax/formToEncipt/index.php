<?php
// Si llega un POST (lo manda el fetch de abajo), encripta y responde JSON, nada de HTML.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $clave = $_POST['clave'] ?? '';

    echo json_encode([
        'md5'    => md5($clave),
        'sha256' => hash('sha256', $clave),
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encriptar clave - AJAX</title>
</head>
<body>

    <h1>Encriptar clave</h1>

    <form id="formulario">
        <label for="clave">Clave a encriptar</label>
        <input type="text" id="clave" name="clave" required>
        <button type="submit">Obtener encriptación</button>
    </form>

    <div id="resultado"></div>

    <script>
        document.getElementById('formulario').addEventListener('submit', function (evento) {
            evento.preventDefault();

            const datos = new FormData(evento.target);

            fetch(window.location.href, {
                method: 'POST',
                body: datos
            })
                .then(function (respuesta) {
                    return respuesta.json();
                })
                .then(function (json) {
                    document.getElementById('resultado').innerHTML =
                        '<p><b>MD5:</b> ' + json.md5 + '</p>' +
                        '<p><b>SHA-256:</b> ' + json.sha256 + '</p>';
                });
        });
    </script>

</body>
</html>
