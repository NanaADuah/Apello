<?php
    
    session_start();
    include("classes/connect.php");
    include("classes/login.php");
    include("classes/user.php");
    include("classes/post.php");
    include("classes/image.php");
    include("classes/profile.php");
    include("classes/time.php");

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
        //posting
    if($_SERVER['REQUEST_METHOD'] == "POST"){
    
        $post = new Post();
        $id = $_SESSION['apello_userid'];
        $result = $post->create_post($id, $_POST, $_FILES);
    
        if($result == ""){
            header("Location: profilepage.php");
            die;
        }else{
            echo "<div style='text-align:center; font-size:12px;color:white;background-color:grey;'>";
            echo "<br>The following errors occurred:<br><br>";
            echo $result;
            echo "</div>";
        }
    } 
        //collect posts
        $post = new Post();
        $id = $user_data['userid'];
        $posts = $post->get_post($id);
        $image_class = new Image();

        $user = new User();
    $friends = $user->get_friends($id);

    $image_class = new Image();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Timeline | Apello</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/post.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/apello_friends.css">
    <link rel="stylesheet" href="css/dark.css">

</head>

<body class="container">
    <!-- top bar -->
    <br>
    <?php
    
    include("header.php");
    
    ?>

    <!-- cover area -->
    <div id="content" style="text-align:center">
       
        <!-- below cover -->

        <div id="content-holder">

            <!-- friends -->
            <div id="friend-content">
                <div id="friend-bar">
                   <a style="text-decoration:none;color:white;font-weight:bold" href="profilepage.php"><?php echo $user_data['first_name'] . "<br>" . $user_data['last_name'] ?></a> 
                </div>
            </div>

            <!-- post area -->
            <div id="post-content">
                <div id="friend-one" style="padding-top:20px">
                <?php

                    if ($friends){
                        foreach ($friends as $FRIEND_ROW){  
                            include("apello_users.php");
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        include("footer.php");
        ?>
    </div>


</body>

</div>

</html>