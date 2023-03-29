<?php

session_start();
include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/image.php");
include("classes/profile.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['apello_userid']);
$USER = $user_data;

$error = "";

$Post = new Post();
$ROW = $Post->get_one_post($_GET['id']);

if (isset($_GET['id'])) {

    if (!$ROW) {
        $error = "Post was not found.";
    } else {

        if ($ROW['userid'] != $_SESSION['apello_userid']) {
            $error = "Access denied. You cannot edit this post";
        }
    }
} else {
    $error = "There was a problem attempting to edit this post.";
}






if($_SERVER['REQUEST_METHOD'] == "POST"){

    $Post->edit_post($_POST, $_FILES);
    $_SESSION['return_to']  = "profilepage.php";
    if (isset($S_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'], "like.php")) {
      $_SESSION['return_to'] = $S_SERVER['HTTP_REFERER'];
    } else {
      echo "<script>history.go(-2)</script>";
    } 
    // if(isset($S_SERVER['HTTP_REFERER'])  && !strstr($_SERVER['HTTP_REFERER'], "edit.php") ){
    //     $return_to = $S_SERVER['HTTP_REFERER'];
    //     header("Location: ".$return_to);
        
    // }else{
        
    //     header("Location: profilepage.php");
    //     }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Post | Apello</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/delete.css">
    <link rel="stylesheet" href="css/image_view.css">
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

        <div id="content-holder" >
            <!-- post area -->
            <div id="post-content" >

                <form method="post" enctype="multipart/form-data">
                    <div id="post-one" style="border-radius:10px">
                        <div><h2 id="delete-post" style="text-align:center">Edit Post</h2></div>
                        <form method="post" enctype="multipart/form-data" >
                        <div>
                            <?php

                                echo '<textarea style="border-radius:10px" name="post" placeholder="What\'s on your mind?"> ' . $ROW['post'] . '</textarea>';
                                if(file_exists($ROW['image'])){
                                    $_ha_s_image = 1;
                                    $post_image = $image_class->get_post_thumb($ROW['image']);
                                    echo "<img src='$post_image' style='text-align:center;margin-bottom:10px;width:50%;' />";
                                }
                                echo "<br style='text-align:center;'>";
                                echo "<div>";
                                echo '<input type="file" name="file" id="upload-button">';
                                
                                echo '<a><button height: 25px;" id="cancel-button">Save</button></a>';
                                echo '<input type="hidden" name="postid" value=' . $ROW['postid'] . '>';
                                echo "</div>";
                                
                            
                            ?>
                        </div>
                    </div>
            </div>

            </form>
        </div>
        </form>
    </div>
    </div>
    </div>


</body>


</html>