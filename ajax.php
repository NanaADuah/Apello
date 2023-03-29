<?php

session_start();
include("classes/notification.php");
include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/image.php");
include("classes/profile.php");

$data = file_get_contents("php://input");

if($data != ""){
    $data = json_decode($data);
}

if(isset($data->action) && ($data->action == "like_post")) {

    include "ajax/like.ajax.php";
}