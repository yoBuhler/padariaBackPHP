<?php

function listUser($data)
{
    global $link;

    if (isAuthenticated()) {
        $returned = array();

        $filter_id = array();
        if ($data !== NULL) {
            if (array_key_exists('id', $data)) {
                if (!(intval(trim($data['id'])) == 0)) {
                    array_push($filter_id, (' id = ' . $data['id']));
                }
            }
            if (array_key_exists('name', $data)) {
                if (!empty(trim($data['name']))) {
                    array_push($filter_id, (" name LIKE '%" . $data['name'] . "%'"));
                }
            }
            if (array_key_exists('active', $data)) {
                if (is_bool($data['active'])) {
                    if ($data['active']) {
                        array_push($filter_id, (" active = TRUE "));
                    } else {
                        array_push($filter_id, (" active = FALSE "));
                    }
                }
            }
        }

        $sql = "SELECT id, name, birth, AES_DECRYPT(login, 'CriptoDaPadoca') as login, cpf, mail, type, active FROM user" . (empty($filter_id) ? '' : ' WHERE ' . implode(' AND ', $filter_id));
        $result = mysqli_query($link, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $currentRow = array();
            $currentRow['id'] = (int) $row['id'];
            $currentRow['name'] = $row['name'];
            $currentRow['birth'] = $row['birth'] == NULL ? $row['birth'] : (new Datetime($row['birth']))->format(DATE_ATOM);
            $currentRow['login'] = $row['login'];
            $currentRow['cpf'] = $row['cpf'];
            $currentRow['mail'] = $row['mail'];
            $currentRow['type'] = $row['type'];
            $currentRow['active'] = (bool) $row['active'];
            array_push($returned, $currentRow);
        }
        // Close connection
        mysqli_close($link);

        echo json_encode($returned);
    }
}
