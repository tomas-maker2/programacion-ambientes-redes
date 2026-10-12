<?php
sleep(rand(3, 5));
$persona = (object) $_POST;
header('Content-Type: application/json');
echo json_encode($persona);
