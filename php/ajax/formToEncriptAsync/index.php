<?php
// Si llega un POST, simula una demora y responde JSON, nada de HTML.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $clave  = $_POST['clave'] ?? '';
    $demora = rand(3, 5);
    sleep($demora);

    echo json_encode([
        'md5'    => md5($clave),
        'sha256' => hash('sha256', $clave),
        'demora' => $demora,
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encriptar clave - AJAX asincrónico</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 30px 20px;
            background-color: #f4f4f4;
            color: #1a1a1a;
        }

        h1 { color: #2e7d32; }

        #divEntrada, #divBoton, #divResultado, #divEstado {
            background-color: #ffffff;
            border: 1px solid #d9d9d9;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
            overflow-wrap: break-word;
        }

        label { display: block; margin-bottom: 6px; font-size: 14px; color: #555555; }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #bbbbbb;
            border-radius: 4px;
            font-size: 15px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #2e7d32;
            color: #ffffff;
            font-size: 15px;
            cursor: pointer;
        }

        button:hover { background-color: #1f5722; }

        #divEstado.ok { color: #2e7d32; font-weight: bold; }
        #divEstado.error { color: #c62828; font-weight: bold; }
    </style>
</head>
<body>

    <h1>Encriptar clave (asincrónico)</h1>

    <div id="divEntrada">
        <label for="clave">Clave a encriptar</label>
        <input type="text" id="clave">
    </div>

    <div id="divBoton">
        <button type="button" id="btnEncriptar">Encriptar</button>
    </div>

    <div id="divResultado">Resultado: todavía no se envió nada.</div>

    <div id="divEstado">Estado del requerimiento: todavía no se envió nada.</div>

    <script>
        document.getElementById('btnEncriptar').addEventListener('click', function () {
            const clave = document.getElementById('clave').value;

            const divResultado = document.getElementById('divResultado');
            const divEstado = document.getElementById('divEstado');

            divResultado.textContent = 'Esperando respuesta...';
            divEstado.textContent = 'Esperando respuesta...';
            divEstado.className = '';

            const datos = new FormData();
            datos.append('clave', clave);

            fetch(window.location.href, {
                method: 'POST',
                body: datos
            })
                .then(function (respuesta) {
                    return respuesta.json();
                })
                .then(function (json) {
                    divResultado.innerHTML =
                        '<p><b>MD5:</b> ' + json.md5 + '</p>' +
                        '<p><b>SHA-256:</b> ' + json.sha256 + '</p>';

                    divEstado.textContent = 'Se cumplió';
                    divEstado.className = 'ok';
                })
                .catch(function () {
                    divEstado.textContent = 'No se cumplió';
                    divEstado.className = 'error';
                });
        });
    </script>

</body>
</html>
