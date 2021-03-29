<?php
// Include config file
require_once "config.php";
require_once "util.php";

function updateUser($data)
{
    global $link;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isAuthenticated()) {
            $returned = array();
            if (paramsIsValid($data, array(['login', 'str']))) {
                $data['login'] = trim($data['login']);
                if ($data['login'] !== $_SESSION['currentUser']->login) {
                    if (!loginAlreadyExists($data['login'], $link)) {
                        $sql = "UPDATE user SET login='" . $data['login'] . "' WHERE id = " . $_SESSION['currentUser']->id;
                        if (mysqli_query($link, $sql) === true) {
                            $_SESSION['currentUser']->login = $data['login'];
                            $returned['login'] = $_SESSION['currentUser']->login;
                        } else {
                            $returned['errorLogin'] = 'ERROR: This login ' . $data['login'] . ' cannot updated';
                        }
                    } else {
                        $returned['errorLogin'] = 'ERROR: This login ' . $data['login'] . ' already exists';
                    }
                }
            }
            if (paramsIsValid($data, array(['password', 'str']))) {
                $data['password'] = trim($data['password']);
                $sql = "UPDATE user SET password='" . password_hash($data['password'], PASSWORD_DEFAULT) . "' WHERE id = " . $_SESSION['currentUser']->id;
                if (mysqli_query($link, $sql) === true) {
                    $returned['password'] = $data['password'];
                } else {
                    $returned['errorPassword'] = 'ERROR: This password ' . $data['password'] . ' cannot updated';
                }
            }
            if (paramsIsValid($data, array(['cpf', 'str']))) {
                $data['cpf'] = trim($data['cpf']);
                if ($data['cpf'] !== $_SESSION['currentUser']->cpf) {
                    if (strlen($data['cpf']) === 11) {
                        $sql = "UPDATE user SET cpf='" . $data['cpf'] . "' WHERE id = " . $_SESSION['currentUser']->id;
                        if (mysqli_query($link, $sql) === true) {
                            $_SESSION['currentUser']->cpf = $data['cpf'];
                            $returned['cpf'] = $_SESSION['currentUser']->cpf;
                        } else {
                            $returned['errorCpf'] = 'ERROR: This cpf ' . $data['cpf'] . ' cannot updated';
                        }
                    } else {
                        $returned['errorCpf'] = 'ERROR: This cpf length ' . $data['cpf'] . ' is different from 11 characters';
                    }
                }
            }
            if (paramsIsValid($data, array(['name', 'str']))) {
                $data['name'] = trim($data['name']);
                if ($data['name'] !== $_SESSION['currentUser']->name) {
                    $sql = "UPDATE user SET name='" . $data['name'] . "' WHERE id = " . $_SESSION['currentUser']->id;
                    if (mysqli_query($link, $sql) === true) {
                        $_SESSION['currentUser']->name = $data['name'];
                        $returned['name'] = $_SESSION['currentUser']->name;
                    } else {
                        $returned['errorName'] = 'ERROR: This name ' . $data['name'] . ' cannot updated';
                    }
                }
            }
            if (paramsIsValid($data, array(['mail', 'str']))) {
                $data['mail'] = trim($data['mail']);
                if ($data['mail'] !== $_SESSION['currentUser']->mail) {
                    $sql = "UPDATE user SET mail='" . $data['mail'] . "' WHERE id = " . $_SESSION['currentUser']->id;
                    if (mysqli_query($link, $sql) === true) {
                        $_SESSION['currentUser']->mail = $data['mail'];
                        $returned['mail'] = $_SESSION['currentUser']->mail;
                    } else {
                        $returned['errorMail'] = 'ERROR: This mail ' . $data['mail'] . ' cannot updated';
                    }
                }
            }
            if (paramsIsValid($data, array(['type', 'str']))) {
                $data['type'] = trim($data['type']);
                if ($data['type'] !== $_SESSION['currentUser']->type) {
                    $sql = "UPDATE user SET type='" . $data['type'] . "' WHERE id = " . $_SESSION['currentUser']->id;
                    if (mysqli_query($link, $sql) === true) {
                        $_SESSION['currentUser']->type = $data['type'];
                        $returned['type'] = $_SESSION['currentUser']->type;
                    } else {
                        $returned['errorType'] = 'ERROR: This type ' . $data['type'] . ' cannot updated';
                    }
                }
            }
            if (paramsIsValid($data, array(['birth', 'str']))) {
                $data['birth'] = trim($data['birth']);
                if (dateIsValid($data['birth'])) {
                    $data['birth'] = convertToDate($data['birth']);
                    $data['birth'] = $data['birth']->format('Y-m-d H:i:s');
                    if ($data['birth'] !== $_SESSION['currentUser']->birth) {
                        $sql = "UPDATE user SET birth='" . $data['birth'] . "' WHERE id = " . $_SESSION['currentUser']->id;
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
