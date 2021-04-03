<?php


$path = 'testejpg.jpg';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);




$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

echo $base64;

file_put_contents('base64.txt', $base64);

if (count(explode(';', $base64)) == 2) {
    list($type, $dataImg) = explode(';', $base64);
    if (count(explode(',', $dataImg)) == 2) {
        list(, $dataImg)      = explode(',', $dataImg);
        $dataImg = base64_decode($dataImg);

        file_put_contents('img.' . mb_split('/', $type)[1], $dataImg);

        $path = 'img.' . mb_split('/', $type)[1];
        $dataImg = file_get_contents($path);
    }
}
