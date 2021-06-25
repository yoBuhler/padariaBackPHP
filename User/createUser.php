<?php

function createUser($data)
{
    // Define variables and initialize with empty values
    $login = $password = "";
    $login_err = $password_err = "";

    global $link;

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Validate login
        if (empty(trim($data["login"]))) {
            $login_err = "Please enter a login.";
        } else {
            // Prepare a select statement
            $sql = "SELECT id FROM user WHERE AES_DECRYPT(login, 'CriptoDaPadoca') = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_login);

                // Set parameters
                $param_login = trim($data["login"]);

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    /* store result */
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $login_err = "This login is already taken.";
                    } else {
                        $login = trim($data["login"]);
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }

        // Validate password
        if (empty(trim($data["password"]))) {
            $password_err = "Please enter a password.";
        } else {
            $password = trim($data["password"]);
        }
        // Check input errors before inserting in database
        if (empty($login_err) && empty($password_err)) {

            // Prepare an insert statement
            $sql = "INSERT INTO user (login, password, active) VALUES (AES_ENCRYPT(?, 'CriptoDaPadoca'), ?, TRUE)";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_login, $param_password);
                // mysqli_stmt_bind_param($stmt, "s", $param_password);

                // Set parameters
                $param_login = $login;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Redirect to login page
                    $sql = "SELECT id FROM user WHERE login = AES_ENCRYPT('" . $param_login . "', 'CriptoDaPadoca') and password = '" . $param_password . "'";
                    if ($result = mysqli_query($link, $sql)) {
                        $currentUser = mysqli_fetch_assoc($result);
                        $return = new stdClass();
                        $return->id = $currentUser["id"];
                        echo json_encode($return);
                        $result->close();
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            } else {
                echo $link->error;
            }
        } else {
            $return = new stdClass();
            if (!empty($login_err)) {
                $return->errorlogin = 'ERROR: ' . $login_err;
            }
            if (!empty($password_err)) {
                $return->errorPassword = 'ERROR: ' . $password_err;
            }
            echo json_encode($return);
        }

        // Close connection
        mysqli_close($link);
    }
}
