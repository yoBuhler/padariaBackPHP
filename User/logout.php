<?php

function logout()
{
    if (session_id() == '') {
        session_start();
    }

    session_unset();
    session_destroy();

    session_write_close();
    
}
