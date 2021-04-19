<?php

// Include router class
include('route.php');
include('config.php');
include('util.php');

// Users Class
include('User/login.php');
include('User/createUser.php');
include('User/updateUser.php');
include('User/listUser.php');

// Products Class
include('Product/createProduct.php');
include('Product/updateProduct.php');
include('Product/listProduct.php');

// Orders Class
include('Order/createOrder.php');
include('Order/updateOrder.php');
include('Order/listOrder.php');

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

Route::add('/user/list/([0-9]*)', function ($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty(trim($id))) {
        $data['id'] = $id;
    }
    listUser($data);
}, array('post', 'get'));

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


Route::add('/order/update/', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    updateOrder($data);
}, array('post'));

Route::add('/order/list/([0-9]*)', function ($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty(trim($id))) {
        $data['id'] = $id;
    }
    listOrder($data);
}, array('post', 'get'));

Route::run('/');
