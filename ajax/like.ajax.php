<?php

// $login = new Login();
// $user_data = $login->check_login
if(!isset($_SESSION['apello_userid'])){
  die;
}
if(isset($data->link)){
  $query_string = explode("?", $data->link);

  $query_string = end($query_string);
  
  // print_r($data);
  // echo $query_string;
  $str = explode("&", $query_string);
  
  foreach ($str as $value) {
    $value = explode("=",$value);
    $_GET[$value[0]] = $value[1];
  }
}

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

  //read likes
  $likes = $post->get_likes($_GET['id'],$_GET['type']);
  $obj = (object)[];

  $obj->likes = count($likes);
  $obj->action = 'like_post';
  echo json_encode($obj);

  // $likes_count = 
  // echo $likes_count;

}
