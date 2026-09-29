<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP básico - variables y tipos</title>
    <style>
        .var { color: #1a56db; }
        .caja {
            background-color: #cfe2f3;
            color: #1155cc;
            text-align: center;
            padding: 10px;
            margin: 15px 0;
        }
    </style>
</head>
<body>

Esto es texto escrito fuera de las marcas de php. Es entregado en la respuesta http sin pasar por el preprocesador php

<hr>

<?php
echo "Texto y/o HTML entregado por el procesador php usando la sentencia echo.";
?>

<hr>

<?php
function mostrarVariable(string $nombre, $valor): void
{
    $texto = is_bool($valor) ? ($valor ? '1' : '0') : (is_array($valor) ? implode(', ', $valor) : $valor);
    echo "<p><b>El valor de <span class=\"var\">\${$nombre}</span> es: {$texto}</b></p>";
    echo "<p><b>El tipo de <span class=\"var\">\${$nombre}</span> es: " . gettype($valor) . "</b></p>";
}

$variableA = "valor1";
$variableB = 2;
$variableC = 3;
$variableD = $variableB + $variableC;
$variableE = true;
$variableF = 3.14;
$variableG = ["rojo", "verde", "azul"];

mostrarVariable('variableA', $variableA);
?>

<hr>

<?php
mostrarVariable('variableB', $variableB);
mostrarVariable('variableC', $variableC);
?>

<div class="caja">variableD es la suma de variableB y variableC</div>
<div class="caja">Si los tipos fueran diferentes Php devolveria error.</div>

<?php
mostrarVariable('variableD', $variableD);
?>

<hr>

<?php
echo "<p><b>variable tipo booleanas o logicas (verdadero) <span class=\"var\">\$variableE</span> : " . ($variableE ? '1' : '0') . "</b></p>";
echo "<p><b>El tipo de <span class=\"var\">\$variableE</span> es: " . gettype($variableE) . "</b></p>";
?>

<hr>

<?php
mostrarVariable('variableF', $variableF);
?>

<hr>

<?php
mostrarVariable('variableG', $variableG);
?>

</body>
</html>
