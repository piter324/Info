<?php
session_start();
$_SESSION['username']='';
$_SESSION['userid']='';
$_SESSION['email']='';
session_destroy();
echo 1;
?>