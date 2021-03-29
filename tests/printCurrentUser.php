<?php 
    if (session_id() == ''){
        session_start();
    }
    print_r($_SESSION['currentUser']);
?>