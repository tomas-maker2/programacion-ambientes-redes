<?php
/**
 * Portada de la materia — Programación en Ambientes de Redes.
 * Muestra el intercambio HTTP que sirvió esta misma página y lista las clases.
 */

/** Acorta un valor largo para que la transcripción siga siendo legible. */
function corto(string $v, int $max = 64): string
{
    return strlen($v) > $max ? substr($v, 0, $max - 1) . '…' : $v;
}

function e(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

// --- Request: lo que mandó el navegador ---------------------------------
$metodo   = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$ruta     = $_SERVER['REQUEST_URI'] ?? '/redes/';
$protocolo = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';

$headersRequest = [];
foreach (['HTTP_HOST' => 'Host', 'HTTP_USER_AGENT' => 'User-Agent', 'HTTP_ACCEPT' => 'Accept', 'HTTP_ACCEPT_LANGUAGE' => 'Accept-Language', 'HTTP_CONNECTION' => 'Connection'] as $clave => $nombre) {
    if (!empty($_SERVER[$clave])) {
        $headersRequest[$nombre] = corto($_SERVER[$clave]);
    }
}

// --- Response: lo que devuelve Apache ------------------------------------
$headersResponse = [
    'Date'         => gmdate('D, d M Y H:i:s') . ' GMT',
    'Server'       => corto($_SERVER['SERVER_SOFTWARE'] ?? 'Apache'),
    'X-Powered-By' => 'PHP/' . PHP_VERSION,
    'Content-Type' => 'text/html; charset=UTF-8',
];

// --- Clases: cada subcarpeta es una clase --------------------------------
$descripciones = [
    'clase1' => 'HTTP: métodos, headers y códigos de estado',
    'html'   => 'Ejercicios: lista, tabla y formulario',
    'css'    => 'Ejercicio de CSS: formulario con estilos',
    'css2'   => 'Ejercicio de CSS: formulario responsivo con media queries',
];

$clases = [];
foreach (glob(__DIR__ . '/*', GLOB_ONLYDIR) as $dir) {
    $nombre = basename($dir);
    if ($nombre[0] === '.') {
        continue;
    }
    $clases[] = [
        'slug'    => $nombre,
        'detalle' => $descripciones[$nombre] ?? null,
    ];
}
sort($clases);

// --- Estado del entorno ---------------------------------------------------
$mysql = @fsockopen('127.0.0.1', 3306, $errno, $errstr, 0.3);
$mysqlActivo = (bool) $mysql;
if ($mysql) {
    fclose($mysql);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Programación en Ambientes de Redes</title>
<style>
:root {
  --paper:  #E9EDF2;
  --card:   #FBFCFD;
  --ink:    #14202F;
  --muted:  #5D6E82;
  --rule:   #C9D3DE;
  --req:    #9A2B68;
  --res:    #0F6B72;

  --mono: "Cascadia Code", "Cascadia Mono", Consolas, ui-monospace, monospace;
  --sans: "Segoe UI Variable Text", "Segoe UI", system-ui, sans-serif;
}

@media (prefers-color-scheme: dark) {
  :root {
    --paper: #0E1622;
    --card:  #16202E;
    --ink:   #E4EAF1;
    --muted: #8B9BAF;
    --rule:  #2A3849;
    --req:   #E8709E;
    --res:   #4FBFC7;
  }
}

* { box-sizing: border-box; }

body {
  margin: 0;
  padding: clamp(1.5rem, 5vw, 4.5rem) clamp(1.25rem, 5vw, 3rem) 4rem;
  background: var(--paper);
  color: var(--ink);
  font-family: var(--sans);
  font-size: 16px;
  line-height: 1.6;
  -webkit-font-smoothing: antialiased;
}

.wrap { max-width: 62rem; margin: 0 auto; }

/* --- Encabezado --- */
.eyebrow {
  font-family: var(--mono);
  font-size: 0.8rem;
  letter-spacing: 0.08em;
  color: var(--muted);
  margin: 0 0 1.25rem;
}

.titulo {
  font-family: var(--mono);
  font-size: clamp(2rem, 6.5vw, 3.9rem);
  font-weight: 700;
  letter-spacing: -0.045em;
  line-height: 1.02;
  margin: 0 0 1rem;
  text-wrap: balance;
}

.bajada {
  max-width: 34rem;
  color: var(--muted);
  margin: 0 0 3rem;
}

/* --- Transcripción HTTP (elemento firma) --- */
.intercambio {
  background: var(--card);
  border: 1px solid var(--rule);
  border-radius: 6px;
  overflow: hidden;
  font-family: var(--mono);
  font-size: 0.85rem;
  line-height: 1.75;
}

.tramo { padding: 1.25rem clamp(1rem, 3vw, 1.75rem); }
.tramo + .tramo { border-top: 1px solid var(--rule); }
.tramo--req { border-left: 3px solid var(--req); }
.tramo--res { border-left: 3px solid var(--res); }

.tramo__label {
  font-size: 0.7rem;
  letter-spacing: 0.14em;
  font-weight: 700;
  margin-bottom: 0.7rem;
}
.tramo--req .tramo__label { color: var(--req); }
.tramo--res .tramo__label { color: var(--res); }

.linea {
  display: block;
  overflow-x: auto;
  white-space: pre;
  animation: entra 0.4s ease-out both;
  animation-delay: calc(var(--i) * 45ms);
}

.linea--inicial { font-weight: 700; margin-bottom: 0.35rem; }
.clave { color: var(--muted); }

.pie-nota {
  font-size: 0.82rem;
  color: var(--muted);
  margin: 0.9rem 0 3.5rem;
  max-width: 40rem;
}

@keyframes entra {
  from { opacity: 0; transform: translateY(3px); }
  to   { opacity: 1; transform: none; }
}

/* --- Clases --- */
h2 {
  font-family: var(--mono);
  font-size: 0.78rem;
  letter-spacing: 0.16em;
  font-weight: 700;
  color: var(--muted);
  margin: 0 0 0.5rem;
}

.clases { list-style: none; margin: 0 0 3rem; padding: 0; }

.clases li { border-top: 1px solid var(--rule); }
.clases li:last-child { border-bottom: 1px solid var(--rule); }

.clases a {
  display: flex;
  align-items: baseline;
  gap: 1rem;
  padding: 1rem 0.25rem;
  text-decoration: none;
  color: inherit;
}

.clases a:hover .slug,
.clases a:focus-visible .slug { color: var(--res); }

.clases a:focus-visible {
  outline: 2px solid var(--res);
  outline-offset: 2px;
}

.slug {
  font-family: var(--mono);
  font-weight: 700;
  transition: color 0.15s;
}

.detalle { color: var(--muted); font-size: 0.92rem; }
.flecha { margin-left: auto; color: var(--muted); font-family: var(--mono); }

.vacio {
  border: 1px dashed var(--rule);
  border-radius: 6px;
  padding: 1.5rem;
  color: var(--muted);
  font-size: 0.92rem;
  margin-bottom: 3rem;
}
.vacio code {
  font-family: var(--mono);
  color: var(--ink);
}

/* --- Estado del entorno --- */
.entorno {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem 2rem;
  border-top: 1px solid var(--rule);
  padding-top: 1.25rem;
  font-family: var(--mono);
  font-size: 0.76rem;
  color: var(--muted);
}

.entorno b { color: var(--ink); font-weight: 700; }
.on  { color: var(--res); }
.off { color: var(--req); }

@media (prefers-reduced-motion: reduce) {
  .linea { animation: none; }
  .slug  { transition: none; }
}
</style>
</head>
<body>
<div class="wrap">

  <p class="eyebrow">localhost/redes</p>
  <h1 class="titulo">Programación en<br>Ambientes de Redes</h1>
  <p class="bajada">
    Trabajos prácticos de la materia, servidos desde XAMPP.
    Abajo está el intercambio HTTP que trajo esta página.
  </p>

  <?php $i = 0; ?>
  <div class="intercambio">

    <section class="tramo tramo--req">
      <p class="tramo__label">EL NAVEGADOR PIDIÓ</p>
      <code class="linea linea--inicial" style="--i:<?= $i++ ?>"><?= e("$metodo $ruta $protocolo") ?></code>
      <?php foreach ($headersRequest as $nombre => $valor): ?>
        <code class="linea" style="--i:<?= $i++ ?>"><span class="clave"><?= e($nombre) ?>:</span> <?= e($valor) ?></code>
      <?php endforeach; ?>
    </section>

    <section class="tramo tramo--res">
      <p class="tramo__label">APACHE RESPONDIÓ</p>
      <code class="linea linea--inicial" style="--i:<?= $i++ ?>"><?= e("$protocolo 200 OK") ?></code>
      <?php foreach ($headersResponse as $nombre => $valor): ?>
        <code class="linea" style="--i:<?= $i++ ?>"><span class="clave"><?= e($nombre) ?>:</span> <?= e($valor) ?></code>
      <?php endforeach; ?>
    </section>

  </div>
  <p class="pie-nota">
    Estos valores salen de <code>$_SERVER</code> en el momento en que PHP arma la página.
    Recargá y mirá cómo cambia <code>Date</code>.
  </p>

  <h2>CLASES</h2>
  <?php if ($clases): ?>
    <ul class="clases">
      <?php foreach ($clases as $clase): ?>
        <li>
          <a href="<?= e($clase['slug']) ?>/">
            <span class="slug"><?= e($clase['slug']) ?></span>
            <?php if ($clase['detalle']): ?>
              <span class="detalle"><?= e($clase['detalle']) ?></span>
            <?php endif; ?>
            <span class="flecha" aria-hidden="true">→</span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p class="vacio">
      Todavía no hay clases. Creá una carpeta en <code>C:\xampp\htdocs\redes</code>
      y va a aparecer acá sola.
    </p>
  <?php endif; ?>

  <div class="entorno">
    <span>PHP <b><?= e(PHP_VERSION) ?></b></span>
    <span><?= e(corto($_SERVER['SERVER_SOFTWARE'] ?? 'Apache', 28)) ?></span>
    <span>puerto <b><?= e((string) ($_SERVER['SERVER_PORT'] ?? '80')) ?></b></span>
    <span>MySQL <b class="<?= $mysqlActivo ? 'on' : 'off' ?>"><?= $mysqlActivo ? 'activo' : 'detenido' ?></b></span>
  </div>

</div>
</body>
</html>
