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
    
   
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search | Apello</title>
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

                        $result = "Error getting results";
                        
                        if(isset($_GET['find'])){

                            $find = addslashes($_GET['find']);
                    
                            $sql = "SELECT * FROM users WHERE first_name LIKE '%$find%' ||  last_name LIKE '%$find%' LIMIT 30";
                    
                            $DB = new Database();
                            $result = $DB->read($sql);
                        }

                        $User = new User();
                        $image_class = new Image();

                            if(is_array($result)){
                                foreach($result as $row){
                                    $FRIEND_ROW = $User->get_user($row['userid']);
                                    include('user.php');

                                    }
                                }else{
                                    echo "No results have been found";
                            }

                        ?><br style="clear:both">
                    </div>
                </form>
            </div>
        </div>
    </div>


</body>

</div>

</html>