<?php
session_start(); 
$rand = rand(0,4);
$_SESSION['captcha'] = $rand;
echo $rand;
?>



