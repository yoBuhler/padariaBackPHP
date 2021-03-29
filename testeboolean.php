<?php
$data = json_decode(file_get_contents('php://input'), true);
echo (int) $data['boolean'];