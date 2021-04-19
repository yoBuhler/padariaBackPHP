<?php

function listProduct($data)
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
                        array_push($filter_id, (' active = TRUE '));
                    } else {
                        array_push($filter_id, (' active = FALSE '));
                    }
                }
            }
        }

        $sql = 'SELECT * FROM product' . (empty($filter_id) ? '' : ' WHERE ' . implode(' AND ', $filter_id));
        $result = mysqli_query($link, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $currentRow = array();
            $currentRow['id'] = (int) $row['id'];
            $currentRow['name'] = $row['name'];
            $currentRow['description'] = $row['description'];
            $currentRow['shortDescription'] = $row['shortDescription'];
            $currentRow['price'] = (float) $row['price'];
            $currentRow['quantityAvailable'] = (float) $row['quantityAvailable'];
            $currentRow['active'] = (bool) $row['active'];
            $currentRow['imagebase64'] = 'data:image/' . $row['image_type'] . ';base64,' . base64_encode($row['image']);
            array_push($returned, $currentRow);
        }
        // Close connection
        mysqli_close($link);

        echo json_encode($returned);
    }
}
