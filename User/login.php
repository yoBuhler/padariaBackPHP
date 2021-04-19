<?php

function login($data)
{
    // Define variables and initialize with empty values
    $login = $password = "";
    global $link;
    $returned = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        [$loginAndPasswordIsValid, $returned] = paramsIsValid($data, array(['login', 'str'], ['password', 'str']), $returned);
        if ($loginAndPasswordIsValid) {
            $login = $data['login'];
            $password = $data['password'];

            $sql = "SELECT id, name, birth, login, cpf, mail, type, password FROM user WHERE login = '" . $login . "' and active";
            if ($result = mysqli_query($link, $sql)) {
                $currentUser = mysqli_fetch_assoc($result);
                if (password_verify($password, $currentUser['password'])) {
                    $returned = array();
                    session_start();
                    $returned['id'] = $currentUser['id'];
                    $returned['name'] = $currentUser['name'];
                    $returned['birth'] = $currentUser['birth'] == NULL ? $currentUser['birth'] : (new Datetime($currentUser['birth']))->format(DATE_ATOM);
                    $returned['login'] = $currentUser['login'];
                    $returned['cpf'] = $currentUser['cpf'];
                    $returned['mail'] = $currentUser['mail'];
                    $returned['type'] = $currentUser['type'];
                    $_SESSION['currentUser'] = $returned;
                } else {
                    $returned['errorPassword'] = 'ERROR: Invalid password';
                }
                $result->close();
            }
        }
        // Close connection
        mysqli_close($link);
    }
    if (!empty($returned)) {
        echo json_encode($returned);
    }
}
