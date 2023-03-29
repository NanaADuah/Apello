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
    
        
    $Post = new Post;
    $error = "";
    $ROW = false;

    if(isset($_GET['id'])){
        
        $ROW = $Post->get_one_post($_GET['id']);

        }else{

            $error = "No imge was found";
    }

    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Image | Apello</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <!-- <link rel="stylesheet" href="css/delete.css"> -->
    <link rel="stylesheet" href ="css/likes.css">
    <link rel="stylesheet" href="css/footer.css">
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
            <div id="image-content">
                
                <form method="post" enctype="multipart/form-data">
                    <div id="image-one">

                        <?php
                        
                        $image_class = new Image();

                            if(is_array($ROW)){
                                echo "<img src='$ROW[image]' style='width:100%' >";
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