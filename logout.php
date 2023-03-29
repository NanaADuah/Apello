<?php

session_start();

if(isset($_SESSION['apello_userid'])){

$_SESSION['apello_userid'] = NULL;
unset($_SESSION['apello_userid']);
}

header("Location: login.php");
die;