<?php
// Include config file
require_once "config.php";
require_once "util.php";

function listProduct($data)
{
    global $link;

    if (isAuthenticated()) {
        $returned = array();

        if (intval(trim($data['id'])) == 0) {
            $sql = 'SELECT * FROM product';
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
        } else {
            $sql = "SELECT * FROM product WHERE id = " . $data['id'];
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
        }
        // Close connection
        mysqli_close($link);

        echo json_encode($returned);
    }
}
