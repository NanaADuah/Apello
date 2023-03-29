<?php

session_start();
include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/image.php");
include("classes/time.php");
include("classes/profile.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['apello_userid']);
$USER = $user_data;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $profile = new Profile();
    $profile_data = $profile->get_profile($_GET['id']);

    if (is_array($profile_data)) {
        $user_data = $profile_data[0];
    }
}

$Post = new Post;
$error = "";
$likes = false;

if (isset($_GET['id']) && isset($_GET['type'])) {

    $likes = $Post->get_likes($_GET['id'], $_GET['type']);
} else {

    $error = "Such data isn't available at the moment";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>People | Apello</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/delete.css">
    <link rel="stylesheet" href="css/likes.css">
    <link rel="stylesheet" href="css/footer.css">
</head>

<body>
    <!-- top bar -->
    <br>
    <?php

    include("header.php");
    include_once("classes/image.php");



    $corner_image = "images/icons/user_male.jpg";

    if (isset($user_data)) {

        $image_class = new Image();
        $corner_image =  $image_class->get_profile_thumb($user_data['profile_image']);
    }
    ?>

    <!-- cover area -->
    <div id="content">

        <!-- below cover -->

        <div id="content-holder" style=display:inline;>
            <!-- post area -->
            <div id="post-content">

                <form method="post" enctype="multipart/form-data">
                    <div id="post-one">

                        <?php

                        $User = new User();
                        $image_class = new Image();
                        if (isset($_GET['id'])) {
                            $post_id = $_GET['id'];
                            $sql = "SELECT views FROM status WHERE postid = '$post_id'LIMIT 1 ";
                            // echo $sql;
                            $DB = new Database();
                            $result = $DB->read($sql);
                            // print_r($result);
                            // die;
                            $counter = -1;
                            if (is_array($result)) {
                                $views= json_decode($result[0]['views'], true);
                                // print_r($views);
                                if(!empty($views)){
                                $user_ids = array_column($views, "userid");
                                $user_time = array_column($views, "date");

                                if (is_array($user_ids)) {
                                    // $counter = -1;
                                    foreach ($views as $row) {
                                        $counter++;
                                        // print_r($row);
                                        // print_r($row);
                                        // die;
                                        if(isset($row['userid']) && isset($row['date'])){
                                        $FRIEND_ROW = $User->get_user($row['userid']);
                                        $date = $user_time[$counter];
                                        include('user_views.php');
                                    }
                                    }
                                }
                            }else{
                                echo "<div style='text-align:center;font-weight:bold;'> No views</div>";
                            }
                            }
                        }

                        ?><br style="clear:both">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
        include("footer.php");
        ?>


</body>

</div>

</html>