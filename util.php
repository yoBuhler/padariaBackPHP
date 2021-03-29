<?php

function dateIsValid($date)
{
    if (!($date instanceof DateTime)) {
        $date = new DateTime($date);
    }

    if (!($date instanceof DateTime)) {
        $date = date_create_from_format("dd/mm/yyyy hh:mm:ss", $date);
    }

    if (!($date instanceof DateTime)) {
        return false;
    }

    return true;
}

function convertToDate($date)
{
    if (!($date instanceof DateTime)) {
        $date = new DateTime($date);
    }

    if (!($date instanceof DateTime)) {
        $date = date_create_from_format("dd/mm/yyyy hh:mm:ss", $date);
    }

    return $date;
}

function paramsIsValid($data, $arrayNamesAndTypes)
{
    $_returned = true;
    $_validateData = array();
    foreach ($arrayNamesAndTypes as list($key, $type)) {
        if (array_key_exists($key, $data)) {
            if ($type == 'str') {
                if (empty(trim($data[$key]))) {
                    $_returned = false;
                    $_validateData["error" . ucfirst($key)] = 'ERROR: ' . $key . ' is empty';
                }
            } else if ($type == 'int') {
                if (intval(trim($data[$key])) == 0) {
                    $_returned = false;
                    $_validateData["error" . ucfirst($key)] = 'ERROR: ' . $key . ' is not a int';
                }
            } else if ($type == 'double') {
                if (!is_numeric(trim($data[$key]))) {
                    $_returned = false;
                    $_validateData["error" . ucfirst($key)] = 'ERROR: ' . $key . ' is not numeric';
                }
            } else if ($type == 'bool') {
                if (!is_bool(trim($data[$key]))) {
                    $_returned = false;
                    $_validateData["error" . ucfirst($key)] = 'ERROR: ' . $key . ' is not boolean';
                }
            }
        } else {
            $_returned = false;
        }
    }
    if (!empty($_validateData)) {
        echo json_encode($_validateData);
    }

    return $_returned;
}

function isAuthenticated()
{
    if (session_id() == '') {
        session_start();
    }
    if (array_key_exists('currentUser', $_SESSION)) {
        if (!is_null($_SESSION['currentUser'])) {
            return true;
        } else {
            $_returned = array();
            $_returned['errorAuthenticate'] = 'ERROR: This session is null';
            echo json_encode($_returned);
            return false;
        }
    } else {
        $_returned = array();
        $_returned['errorAuthenticate'] = 'ERROR: This session is not authenticate';
        echo json_encode($_returned);
        return false;
    }
}

function loginAlreadyExists($login, $link)
{
    $sql = "SELECT id FROM user WHERE login = ?";
    $_returned = true;
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_login);

        // Set parameters
        $param_login = trim($login);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            /* store result */
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                $_returned = true;
            } else {
                $_returned = false;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    return $_returned;
}
