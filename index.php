<?php

// Include router class
include('route.php');

// Users Class
include('login.php');
include('createUser.php');
include('updateUser.php');
include('updateProduct.php');

// Products Class

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
Route::add('/product/list/([0-9]*)', function ($var1) {
    $data = json_decode(file_get_contents('php://input'), true);
    print_r($data);
    if (!empty($var1)) {
        echo $var1 . ' is a great number!';
    } else {
        echo 'Not was passed a number!';
    }
}, 'post');

// Users Methods

Route::add('/login/', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    login($data);
}, 'post');

Route::add('/user/create/', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    createUser($data);
}, 'post');

Route::add('/user/update/', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    updateUser($data);
}, 'post');

Route::add('/product/update/', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $data['active'] = (bool) $data['active'];
    updateProduct($data);
}, 'post');

Route::run('/');
 