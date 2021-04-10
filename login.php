<?php
// Include config file
require_once "config.php";
require_once "util.php";

function login($data)
{
    // Define variables and initialize with empty values
    $login = $password = "";
    global $link;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (paramsIsValid($data, array(['login', 'str'], ['password', 'str']))) {
            $login = $data['login'];
            $password = $data['password'];

            $sql = "SELECT id, name, birth, login, cpf, mail, type, password FROM user WHERE login = '" . $login . "' and active";
            if ($result = mysqli_query($link, $sql)) {
                $currentUser = mysqli_fetch_assoc($result);
                if (password_verify($password, $currentUser["password"])) {
                    $return = new stdClass();
                    session_start();
                    $return->id = $currentUser["id"];
                    $return->name = $currentUser["name"];
                    $return->birth = $currentUser["birth"] == NULL ? $currentUser["birth"] : (new Datetime($currentUser["birth"]))->format(DATE_ATOM);
                    $return->login = $currentUser["login"];
                    $return->cpf = $currentUser["cpf"];
                    $return->mail = $currentUser["mail"];
                    $return->type = $currentUser["type"];
                    $_SESSION['currentUser'] = $return;
                    echo json_encode($return);
                } else {
                    $return = array();
                    $return["errorPassword"] = 'ERROR: Invalid password';
                    echo json_encode($return);
                }
                $result->close();
            }
        }
        // Close connection
        mysqli_close($link);
    }
}
