<?php

function updateUser($data)
{
    global $link;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isAuthenticated()) {
            $returned = array();
            if (array_key_exists('id', $data)) {
                if (!empty(trim($data['id']))) {
                    $data['id'] = trim($data['id']);
                    if (intval($data['id']) != 0) {
                        $data['id'] = (int) $data['id'];
                    } else {
                        $data['id'] = $_SESSION['currentUser']['id'];
                    }
                } else {
                    $data['id'] = $_SESSION['currentUser']['id'];
                }
            } else {
                $data['id'] = $_SESSION['currentUser']['id'];
            }
            [$loginIsValid, $returned] = paramsIsValid($data, array(['login', 'str']), $returned);
            if ($loginIsValid) {
                $data['login'] = trim($data['login']);
                [$loginAlreadyExists, $returned] = loginAlreadyExists($data['login'], $link, $returned);
                if (!$loginAlreadyExists) {
                    $sql = "UPDATE user SET login=AES_ENCRYPT('" . $data['login'] . "', 'CriptoDaPadoca') WHERE id = " . $data['id'];
                    if (mysqli_query($link, $sql) === true) {
                        if ($data['id'] == $_SESSION['currentUser']['id']) {
                            $_SESSION['currentUser']['login'] = $data['login'];
                        }
                        $returned['login'] = $data['login'];
                    } else {
                        $returned['errorLogin'] = 'ERROR: This login ' . $data['login'] . ' cannot updated';
                    }
                } else {
                    $returned['errorLogin'] = 'ERROR: This login ' . $data['login'] . ' already exists';
                }
            }
            [$passwordIsValid, $returned] = paramsIsValid($data, array(['password', 'str']), $returned);
            if ($passwordIsValid) {
                $data['password'] = trim($data['password']);
                $sql = "UPDATE user SET password='" . password_hash($data['password'], PASSWORD_DEFAULT) . "' WHERE id = " . $data['id'];
                if (mysqli_query($link, $sql) === true) {
                    $returned['password'] = $data['password'];
                } else {
                    $returned['errorPassword'] = 'ERROR: This password ' . $data['password'] . ' cannot updated';
                }
            }
            [$cpfIsValid, $returned] = paramsIsValid($data, array(['cpf', 'str']), $returned);
            if ($cpfIsValid) {
                $data['cpf'] = trim($data['cpf']);
                if (strlen($data['cpf']) === 11) {
                    $sql = "UPDATE user SET cpf='" . $data['cpf'] . "' WHERE id = " . $data['id'];
                    if (mysqli_query($link, $sql) === true) {
                        if ($data['id'] == $_SESSION['currentUser']['id']) {
                            $_SESSION['currentUser']['cpf'] = $data['cpf'];
                        }
                        $returned['cpf'] = $data['cpf'];
                    } else {
                        $returned['errorCpf'] = 'ERROR: This cpf ' . $data['cpf'] . ' cannot updated';
                    }
                } else {
                    $returned['errorCpf'] = 'ERROR: This cpf length ' . $data['cpf'] . ' is different from 11 characters';
                }
            }
            [$nameIsValid, $returned] = paramsIsValid($data, array(['name', 'str']), $returned);
            if ($nameIsValid) {
                $data['name'] = trim($data['name']);
                $sql = "UPDATE user SET name='" . $data['name'] . "' WHERE id = " . $data['id'];
                if (mysqli_query($link, $sql) === true) {
                    if ($data['id'] == $_SESSION['currentUser']['id']) {
                        $_SESSION['currentUser']['name'] = $data['name'];
                    }
                    $returned['name'] = $data['name'];
                } else {
                    $returned['errorName'] = 'ERROR: This name ' . $data['name'] . ' cannot updated';
                }
            }
            [$mailIsValid, $returned] = paramsIsValid($data, array(['mail', 'str']), $returned);
            if ($mailIsValid) {
                $data['mail'] = trim($data['mail']);
                $sql = "UPDATE user SET mail='" . $data['mail'] . "' WHERE id = " . $data['id'];
                if (mysqli_query($link, $sql) === true) {
                    if ($data['id'] == $_SESSION['currentUser']['id']) {
                        $_SESSION['currentUser']['mail'] = $data['mail'];
                    }
                    $returned['mail'] = $data['mail'];
                } else {
                    $returned['errorMail'] = 'ERROR: This mail ' . $data['mail'] . ' cannot updated';
                }
            }
            [$typeIsValid, $returned] = paramsIsValid($data, array(['type', 'str']), $returned);
            if ($typeIsValid) {
                $data['type'] = trim($data['type']);
                $sql = "UPDATE user SET type='" . $data['type'] . "' WHERE id = " . $data['id'];
                if (mysqli_query($link, $sql) === true) {
                    if ($data['id'] == $_SESSION['currentUser']['id']) {
                        $_SESSION['currentUser']['type'] = $data['type'];
                    }
                    $returned['type'] = $data['type'];
                } else {
                    $returned['errorType'] = 'ERROR: This type ' . $data['type'] . ' cannot updated';
                }
            }
            [$activeIsValid, $returned] = paramsIsValid($data, array(['active', 'bool']), $returned);
            if ($activeIsValid) {
                $sql = "UPDATE user SET active = " . (int) $data['active'] . " WHERE id = " . $data['id'];
                if (mysqli_query($link, $sql) === true) {
                    if ($data['id'] == $_SESSION['currentUser']['id']) {
                        $_SESSION['currentUser']['active'] = $data['active'];
                    }
                    $returned['active'] = $data['active'];
                } else {
                    $returned['errorActive'] = 'ERROR: This active ' . $data['active'] . ' cannot updated';
                }
            }
            [$birthIsValid, $returned] = paramsIsValid($data, array(['birth', 'str']), $returned);
            if ($birthIsValid) {
                $data['birth'] = trim($data['birth']);
                if (dateIsValid($data['birth'])) {
                    $data['birth'] = convertToDate($data['birth']);
                    $data['birth'] = $data['birth']->format('Y-m-d H:i:s');
                    $sql = "UPDATE user SET birth='" . $data['birth'] . "' WHERE id = " . $data['id'];
                    if (mysqli_query($link, $sql) === true) {
                        if ($data['id'] == $_SESSION['currentUser']['id']) {
                            $_SESSION['currentUser']['birth'] = $data['birth'];
                        }
                        $returned['birth'] = (new Datetime($data['birth']))->format(DATE_ATOM);
                    } else {
                        $returned['errorBirth'] = 'ERROR: This birth ' . $data['birth'] . ' cannot updated';
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
