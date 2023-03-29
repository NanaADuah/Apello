<?php
    
    session_start();
    include("classes/connect.php");
    include("classes/login.php");
    include("classes/user.php");
    include("classes/post.php");
    include("classes/image.php");
    include("classes/profile.php");

    $login = new Login();
    $user_data = $login -> check_login($_SESSION['apello_userid']);
    $USER = $user_data;

    if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $profile = new Profile();
    $profile_data = $profile->get_profile($_GET['id']);
    
    if(is_array($profile_data)){
    $user_data = $profile_data[0];
    }
}
    
    $Post = new Post;
    $error = "";
    $likes = false;

    if(isset($_GET['id']) && isset($_GET['type'])){
        
        $likes = $Post->get_likes($_GET['id'], $_GET['type']);
        }else{

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
</head>

<body>
    <!-- top bar -->
    <br>
    <?php
    
    include("header.php");
    include_once("classes/image.php");
    
    
    
    $corner_image = "images/icons/user_male.jpg";
    
    if(isset($user_data)){
    
    $image_class = new Image();
    $corner_image =  $image_class->get_profile_thumb($user_data['profile_image']);
    
    }    
    ?>

    <!-- cover area -->
    <div id="content">

        <!-- below cover -->

        <div id="content-holder"   style=display:inline;>
            <!-- post area -->
            <div id="post-content">
                
                <form method="post" enctype="multipart/form-data">
                    <div id="post-one">

                        <?php
                        
                        $User = new User();
                        $image_class = new Image();

                            if(is_array($likes)){
                                foreach($likes as $row){
                                    if(isset($row['userid'])){
                                        $FRIEND_ROW = $User->get_user($row['userid']);
                                        include('user.php');
                                    }
                                }   
                            }

                        ?>
                        <br style="clear:both">
                    </div>
                </form>
            </div>
        </div>
    </div>


</body>

</div>

</html>