<?php
function e(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

// --- Ejercicios de cada carpeta, para el desplegable del nav -------------
$ejercicios = [
    'HTML' => [
        ['href' => 'html/preguntas.html', 'label' => 'Ejercicio 1: lista con las preguntas de HTTP'],
        ['href' => 'html/tabla.html', 'label' => 'Ejercicio 2: tabla de verbos HTTP'],
        ['href' => 'html/formulario.html', 'label' => 'Ejercicio 3: formulario con validaciones'],
        ['href' => 'html/IFrames.html', 'label' => 'Ejercicio 4: iframes'],
    ],
    'CSS' => [
        ['href' => 'css/formulario.html', 'label' => 'Ejercicio 1: el formulario, con estilos'],
        ['href' => 'css2/formulario.html', 'label' => 'Ejercicio 2: el formulario, responsivo con media queries'],
        ['href' => 'css2/tabla.html', 'label' => 'Ejercicio 3: tabla responsiva'],
    ],
    'JavaScript' => [
        ['href' => 'javascript/formulario.html', 'label' => 'Ejercicio 1: formulario interactivo'],
        ['href' => 'javascript/idioma.html', 'label' => 'Ejercicio 2: saludo según el idioma del navegador'],
        ['href' => 'javascript/personas.html', 'label' => 'Ejercicio 3: registro de personas'],
        ['href' => 'javascript/objetos.html', 'label' => 'Ejercicio 4: creación de objetos'],
    ],
    'Especiales' => [
        ['href' => 'ESPECIALES/productos.html', 'label' => 'Ejercicio 1: JSON, tabla y modal de productos'],
        ['href' => 'ESPECIALES/clientes.html', 'label' => 'Ejercicio 2: JSON, tabla y modal de clientes'],
    ],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Programación en Ambientes de Redes</title>
<style>
* { box-sizing: border-box; }

body {
  margin: 0;
  background: #ffffff;
  font-family: system-ui, sans-serif;
}

header {
  background: #ffffff;
  border-bottom: 1px solid #dddddd;
}

nav {
  display: flex;
  flex-wrap: wrap;
}

.menu { position: relative; }

.menu .nombre {
  display: block;
  padding: 1rem 1.25rem;
  font-weight: 700;
  cursor: pointer;
}

.menu:hover .nombre { color: #0F6B72; }

.desplegable {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 10;
  min-width: 280px;
  margin: 0;
  padding: 0;
  list-style: none;
  background: #ffffff;
  border: 1px solid #dddddd;
}

.desplegable li + li { border-top: 1px solid #eeeeee; }

.desplegable a {
  display: block;
  padding: 0.75rem 1rem;
  color: inherit;
  text-decoration: none;
  font-size: 0.9rem;
}

.desplegable a:hover { color: #0F6B72; }

.oculto { display: none; }
</style>
</head>
<body>

<header>
  <nav>
    <?php foreach ($ejercicios as $titulo => $items): ?>
      <div class="menu">
        <span class="nombre"><?= e($titulo) ?></span>
        <ul class="desplegable oculto">
          <?php foreach ($items as $item): ?>
            <li><a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </nav>
</header>

<script src="menu.js" defer></script>
</body>
</html>
