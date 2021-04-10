<?php
// Include config file
require_once "config.php";
require_once "util.php";

function listOrder($data)
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
            if (array_key_exists('user_id', $data)) {
                if (!empty(trim($data['user_id']))) {
                    array_push($filter_id, (' user_id = ' . $data['user_id']));
                }
            }
        }
        $sql_order = 'SELECT * FROM orders' . (empty($filter_id) ? '' : ' WHERE ' . implode(' AND ', $filter_id));
        $result_order = mysqli_query($link, $sql_order);
        while ($row_order = mysqli_fetch_assoc($result_order)) {
            $currentRow_order = array();
            $currentRow_order['id'] = (int) $row_order['id'];
            $currentRow_order['status'] = $row_order['status'];
            $currentRow_order['created_at'] = (new Datetime($row_order['created_at']))->format(DATE_ATOM);
            $sql_user = 'SELECT name, birth, login, cpf, mail, type FROM user WHERE id = ' . (int) $row_order['user_id'];
            $result_user = mysqli_query($link, $sql_user);
            $currentRow_order['user'] = array();
            while ($row_user = mysqli_fetch_assoc($result_user)) {
                $currentRow_user = array();
                $currentRow_user['id'] = (int) $row_order['user_id'];
                $currentRow_user['name'] = $row_user['name'];
                $currentRow_user['birth'] = $row_user['birth'] == NULL ? $row_user['birth'] : (new Datetime($row_user['birth']))->format(DATE_ATOM);
                $currentRow_user['login'] = $row_user['login'];
                $currentRow_user['cpf'] = $row_user['cpf'];
                $currentRow_user['mail'] = $row_user['mail'];
                $currentRow_user['type'] = $row_user['type'];
                array_push($currentRow_order['user'], $currentRow_user);
            }
            $currentRow_order['products'] = array();
            $sql_product_order = 'SELECT * FROM product_order WHERE order_id = ' . $row_order['id'];
            $result_product_order = mysqli_query($link, $sql_product_order);
            while ($row_product_order = mysqli_fetch_assoc($result_product_order)) {
                $currentRow_products = array();
                $sql_product = 'SELECT * FROM product WHERE id = ' . $row_product_order['product_id'];
                $result_product = mysqli_query($link, $sql_product);
                while ($row_product = mysqli_fetch_assoc($result_product)) {
                    $currentRow_products['id'] = (int) $row_product_order['product_id'];
                    $currentRow_products['quantity'] = (float) $row_product_order['quantity'];
                    $currentRow_products['price'] = (float) $row_product_order['price'];
                    $currentRow_products['name'] = $row_product['name'];
                    $currentRow_products['description'] = $row_product['description'];
                    $currentRow_products['shortDescription'] = $row_product['shortDescription'];
                    $currentRow_products['active'] = (bool) $row_product['active'];
                    $currentRow_products['imagebase64'] = 'data:image/' . $row_product['image_type'] . ';base64,' . base64_encode($row_product['image']);
                }
                array_push($currentRow_order['products'], $currentRow_products);
            }
            array_push($returned, $currentRow_order);
        }

        // Close connection
        mysqli_close($link);

        echo json_encode($returned);
    }
}
