<?php
// Include config file
require_once "config.php";
require_once "util.php";

function createOrder($data)
{
    global $link;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isAuthenticated()) {
            $returned = array();
            mysqli_query($link, "INSERT INTO orders (user_id, created_at) VALUES (" . $_SESSION['currentUser']['id'] . ", NOW())");
            $data['id'] = mysqli_insert_id($link);
            $returned['id'] = $data['id'];
            $returned['products'] = array();
            $validateQuantitys = true;
            [$productsIsValid, $returned] = paramsIsValid($data, array(['products', 'arr']), $returned);
            if ($productsIsValid) {
                // Validate quantity and remove quantitys
                foreach ($data['products'] as $row) {
                    $currentBoolToAdd = true;
                    $currentProduct = array();
                    if (array_key_exists('product_id', $row) && array_key_exists('quantity', $row)) {
                        if (intval($row['product_id']) !== 0 && is_numeric($row['quantity'])) {
                            $result = mysqli_query($link, "SELECT quantityAvailable, price FROM product WHERE id = " . ((int) $row['product_id']));
                            if ($rowResultCurrentProduct = mysqli_fetch_assoc($result)) {
                                if (((float) $rowResultCurrentProduct['quantityAvailable']) >= ((float) $row['quantity'])) {
                                    $currentProduct['price'] = (float) $rowResultCurrentProduct['price'];
                                    $currentProduct['quantity'] = (float) $row['quantity'];
                                    $currentProduct['quantityAvailable'] = (float) $rowResultCurrentProduct['quantityAvailable'];
                                    $currentProduct['product_id'] = (int) $row['product_id'];

                                    // Keep only one product_id
                                    $currentFiltered = array_filter($returned['products'], function ($v, $k) {
                                        global $currentProduct;
                                        return $k == 'product_id' && $v == $currentProduct['product_id'];
                                    }, ARRAY_FILTER_USE_BOTH);

                                    if (count($currentFiltered) > 0) {
                                        $currentBoolToAdd = false;
                                        foreach ($returned['products'] as $value) {
                                            if ($value['product_id'] == $currentProduct['product_id']) {
                                                if ($currentProduct['quantityAvailable'] >= ((float) $value['quantity'] + (float) $currentProduct['quantity'])) {
                                                    $value['quantity'] = (float) $currentProduct['quantity'] + (float) $value['quantity'];
                                                } else {
                                                    $validateQuantitys = false;
                                                    $currentProduct['errorProduct'] = "ERROR: Quantity available is lower than this product";
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $validateQuantitys = false;
                                    $currentProduct['errorProduct'] = "ERROR: Quantity available is lower than this product";
                                }
                            } else {
                                $validateQuantitys = false;
                                $currentProduct['errorProduct'] = "ERROR: Dont have a product with this product_id: " . $row['product_id'];
                            }
                        } else {
                            $validateQuantitys = false;
                            $currentProduct['errorProduct'] = "ERROR: Quantity or product_id not is a number";
                        }
                    } else {
                        $validateQuantitys = false;
                        $currentProduct['errorProduct'] = "ERROR: Dont have the keys product_id or quantity in this product";
                    }

                    if ($currentBoolToAdd) {
                        array_push($returned['products'], $currentProduct);
                    }
                }
                if ($validateQuantitys) {
                    foreach ($returned['products'] as $row) {
                        if (array_key_exists('product_id', $row)) {
                            $resultProduct = mysqli_query($link, "UPDATE product SET quantityAvailable = " . ((float) $row['quantityAvailable'] - (float) $row['quantity']) . " WHERE id = " . ((int) $row['product_id']));
                            if ($resultProduct) {
                                mysqli_query($link, "INSERT INTO product_order (product_id, order_id, quantity, price) VALUES (" . $row['product_id'] . ", " . $returned['id'] . ", " . $row['quantity'] . ", " . $row['price'] . ")");
                                $row['id'] = mysqli_insert_id($link);
                            } else {
                                $row['errorProduct'] = "ERROR: Cannot update quantityAvailable of this product";
                            }
                        }
                    }
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
