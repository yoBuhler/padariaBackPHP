<?php
// Include config file
require_once "config.php";
require_once "util.php";

function createProduct($data)
{
    global $link;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isAuthenticated()) {
            $returned = array();
            mysqli_query($link, "INSERT INTO product (active) VALUES (1)");
            $data['id'] = mysqli_insert_id($link);
            $returned['id'] = $data['id'];
            if (paramsIsValid($data, array(['name', 'str']))) {
                $data['name'] = trim($data['name']);
                $sql = "UPDATE product SET name='" . $data['name'] . "' WHERE id = " . $data['id'];
                if (mysqli_query($link, $sql) === true) {
                    $returned['name'] = $data['name'];
                } else {
                    $returned['errorName'] = 'ERROR: This name ' . $data['name'] . ' cannot updated';
                }
            }
            if (paramsIsValid($data, array(['description', 'str']))) {
                $data['description'] = trim($data['description']);
                $sql = "UPDATE product SET description='" . $data['description'] . "' WHERE id = " . $data['id'];
                if (mysqli_query($link, $sql) === true) {
                    $returned['description'] = $data['description'];
                } else {
                    $returned['errorDescription'] = 'ERROR: This description ' . $data['description'] . ' cannot updated';
                }
            }
            if (paramsIsValid($data, array(['shortDescription', 'str']))) {
                $data['shortDescription'] = trim($data['shortDescription']);
                $sql = "UPDATE product SET shortDescription='" . $data['shortDescription'] . "' WHERE id = " . $data['id'];
                if (mysqli_query($link, $sql) === true) {
                    $returned['shortDescription'] = $data['shortDescription'];
                } else {
                    $returned['errorShortDescription'] = 'ERROR: This shortDescription ' . $data['shortDescription'] . ' cannot updated';
                }
            }
            if (paramsIsValid($data, array(['price', 'double']))) {
                $data['price'] = doubleval(trim($data['price']));
                $sql = "UPDATE product SET price = " . $data['price'] . " WHERE id = " . $data['id'];
                if (mysqli_query($link, $sql) === true) {
                    $returned['price'] = $data['price'];
                } else {
                    $returned['errorPrice'] = 'ERROR: This price ' . $data['price'] . ' cannot updated';
                }
            }
            if (paramsIsValid($data, array(['quantityAvailable', 'double']))) {
                $data['quantityAvailable'] = doubleval(trim($data['quantityAvailable']));
                $sql = "UPDATE product SET quantityAvailable = " . $data['quantityAvailable'] . " WHERE id = " . $data['id'];
                if (mysqli_query($link, $sql) === true) {
                    $returned['quantityAvailable'] = $data['quantityAvailable'];
                } else {
                    $returned['errorQuantityAvailable'] = 'ERROR: This quantityAvailable ' . $data['quantityAvailable'] . ' cannot updated';
                }
            }
            if (paramsIsValid($data, array(['active', 'bool']))) {
                $sql = "UPDATE product SET active = " . (int) $data['active'] . " WHERE id = " . $data['id'];
                if (mysqli_query($link, $sql) === true) {
                    $returned['active'] = $data['active'];
                } else {
                    $returned['erroractive'] = 'ERROR: This active ' . $data['active'] . ' cannot updated';
                }
            }
            if (paramsIsValid($data, array(['imagebase64', 'str']))) {
                if (count(explode(';', $data['imagebase64'])) == 2) {
                    list($type, $dataImg) = explode(';', $data['imagebase64']);
                    if (count(explode(',', $dataImg)) == 2) {
                        list(, $dataImg) = explode(',', $dataImg);
                        $dataImg = base64_decode($dataImg);

                        // file_put_contents('img.' . mb_split('/', $type)[1], $dataImg);

                        // $path = 'img.' . mb_split('/', $type)[1];
                        // $dataImg = file_get_contents($path);
                        
                        $sql = "UPDATE product SET image='" . addslashes($dataImg) . "', image_type='" . mb_split('/', $type)[1] . "' WHERE id = " . $data['id'];
                        if (mysqli_query($link, $sql) === true) {
                            $returned['image'] = 'data:image/' . mb_split('/', $type)[1] . ';base64,' . base64_encode($dataImg);
                            $returned['image_type'] = mb_split('/', $type)[1];
                        } else {
                            $returned['errorImagebase64'] = 'ERROR: This imagebase64 ' . $data['imagebase64'] . ' cannot updated';
                        }
                    } else {
                        $returned['errorImagebase64'] = 'ERROR: This imagebase64 ' . $data['imagebase64'] . ' are not a base64 image';
                    }
                } else {
                    $returned['errorImagebase64'] = 'ERROR: This imagebase64 ' . $data['imagebase64'] . ' are not a base64 image';
                }
            }
            // Close connection
            mysqli_close($link);

            if (!empty($returned)) {
                echo json_encode($returned);
            }
        }
    }
}
