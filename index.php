<?php

// Include router class
include('route.php');

// Users Class
include('login.php');
include('createUser.php');
include('updateUser.php');

// Products Class
include('createProduct.php');
include('updateProduct.php');
include('listProduct.php');

// Orders Class
include('createOrder.php');
include('listOrder.php');

// Add base route (startpage)
// Route::add('/', function () {
//     echo 'Welcome :-)';
// });

// // Simple test route that simulates static html file
// Route::add('/test.html', function () {
//     echo 'Hello from test.html';
// });

// // Post route example
// Route::add('/contact-form', function () {
//     echo '<form method="post"><input type="text" name="test" /><input type="submit" value="send" /></form>';
// }, 'get');

// // Post route example
// Route::add('/contact-form', function () {
//     echo 'Hey! The form has been sent:<br/>';
//     print_r($_POST);
// }, 'post');

// Accept only numbers as parameter. Other characters will result in a 404 error
Route::add('/test/list/([0-9]*)', function ($var1) {
    $data = json_decode(file_get_contents('php://input'), true);
    print_r($data);
    if (!empty($var1)) {
        echo $var1 . ' is a great number!';
    } else {
        echo 'Not was passed a number!';
    }
}, array('post'));

// Users Methods

Route::add('/login/', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    login($data);
}, array('post'));

Route::add('/user/create/', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    createUser($data);
}, array('post'));

Route::add('/user/update/', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    updateUser($data);
}, array('post'));

// Product Methods

Route::add('/product/create/', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    createProduct($data);
}, array('post'));

Route::add('/product/update/', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    updateProduct($data);
}, array('post'));

Route::add('/product/list/([0-9]*)', function ($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty(trim($id))) {
        $data['id'] = $id;
    }
    listProduct($data);
}, array('post', 'get'));

// Order Methods

Route::add('/order/create/', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    createOrder($data);
}, array('post'));


Route::add('/order/list/([0-9]*)', function ($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty(trim($id))) {
        $data['id'] = $id;
    }
    listOrder($data);
}, array('post', 'get'));

Route::add('/printArray', function () {
    $data = json_decode(file_get_contents('php://input'), true);

    foreach ($data['array'] as $row) {
        echo($row['id']);
        echo($row['seila']);
    }
}, array('post', 'get'));

Route::run('/');
