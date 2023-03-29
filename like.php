<?php

session_start();
include("classes/notification.php");
include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/image.php");
include("classes/profile.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['apello_userid']);

if (isset($_GET['type']) && isset($_GET['id'])) {
  if (is_numeric($_GET['id'])) {

    $allowed[] = 'post';
    $allowed[] = 'user';
    $allowed[] = 'comment';

    if (in_array($_GET['type'], $allowed)) {
      $post = new Post();
      $user_class = new User();
      $post->like_post($_GET['id'], $_GET['type'], $_SESSION['apello_userid']);

      if ($_GET['type'] == "user") {
        $user_class->follow_user($_GET['id'], $_GET['type'], $_SESSION['apello_userid']);
      }
    }
  }
}
// print_r($_SESSION);
// die;
$_SESSION['return_to']  = "profilepage.php";
if (isset($S_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'], "like.php")) {
  $_SESSION['return_to'] = $S_SERVER['HTTP_REFERER'];
} else {
  echo "<script>history.go(-1)</script>";
}

// echo $_SESSION['return_to'];
// echo "<pre>";
// print_r($_SERVER);
// header("Location:" . $_SESSION['return_to']);

// die;
