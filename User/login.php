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

            $stmt = $link->prepare("SELECT id, name, birth, AES_DECRYPT(login, 'CriptoDaPadoca') as login, cpf, mail, type, password FROM user WHERE AES_DECRYPT(login, 'CriptoDaPadoca') = ? and active");
            $stmt->bind_param('s', $login);
            $stmt->execute();

            $rows = $stmt->get_result();

            if ($rows) {
                $currentUser = mysqli_fetch_assoc($rows);
                if (password_verify($password, $currentUser['password'])) {
                    $returned = array();
                    session_start();
                    $returned['PHPSESSID'] = session_id();
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
                    if (session_id() == '') {
                        session_start();
                    }
                
                    session_unset();
                    session_destroy();
                
                    session_write_close();
                }
            }
            $stmt->close();
        }
        // Close connection
        mysqli_close($link);
    }
    if (!empty($returned)) {
        echo json_encode($returned);
    }
}
