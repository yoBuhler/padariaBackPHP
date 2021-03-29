<?php
// Include config file
require_once "config.php";
require_once "util.php";

function updateUser($data)
{
    global $link;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isAuthenticated() && paramsIsValid($data, array(['id', 'int']))) {
            $returned = array();

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
            if (paramsIsValid($data, array(['birth', 'str']))) {
                $data['birth'] = trim($data['birth']);
                if (dateIsValid($data['birth'])) {
                    $data['birth'] = convertToDate($data['birth']);
                    $data['birth'] = $data['birth']->format('Y-m-d H:i:s');
                    if ($data['birth'] !== $_SESSION['currentUser']->birth) {
                        $sql = "UPDATE product SET birth='" . $data['birth'] . "' WHERE id = " . $data['id'];
                        if (mysqli_query($link, $sql) === true) {
                            $_SESSION['currentUser']->birth = $data['birth'];
                            $returned['birth'] = $_SESSION['currentUser']->birth;
                        } else {
                            $returned['errorBirth'] = 'ERROR: This birth ' . $data['birth'] . ' cannot updated';
                        }
                    }
                } else {
                    $returned['errorBirth'] = 'ERROR: This birth ' . $data['birth'] . ' is not valid';
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
