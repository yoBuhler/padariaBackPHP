<?php 
if (session_id() == ''){
    session_start();
}
echo session_id()

?>