<?php

function updateOrder($data)
{
    global $link;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isAuthenticated()) {
            $returned = array();
            [$idIsValid, $returned] = paramsIsValid($data, array(['id', 'int']), $returned);
            if ($idIsValid) {
                [$statusIsValid, $returned] = paramsIsValid($data, array(['status', 'str']), $returned);
                if ($statusIsValid) {
                    $data['status'] = trim($data['status']);
                    $sql = "UPDATE orders SET status='" . $data['status'] . "' WHERE id = " . $data['id'];
                    if (mysqli_query($link, $sql) === true) {
                        $returned['status'] = $data['status'];
                    } else {
                        $returned['errorStatus'] = 'ERROR: This status ' . $data['status'] . ' cannot updated';
                    }
                }

                // Close connection
                mysqli_close($link);
            }

            if (!empty($returned)) {
                echo json_encode($returned);
            }
        }
    }
}
