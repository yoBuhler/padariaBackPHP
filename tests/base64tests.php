<?php


$path = 'testejpg.jpg';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);




$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

list($type, $data) = explode(';', $base64);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);

file_put_contents('img.' . mb_split('/', $type)[1], $data);

$path = 'img.' . mb_split('/', $type)[1];
$data = file_get_contents($path);