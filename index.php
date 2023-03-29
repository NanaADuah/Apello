<?php
    
    session_start();
    include("classes/connect.php");
    include("classes/login.php");
    include("classes/user.php");
    include("classes/post.php");
    include("classes/image.php");
    include("classes/functions.php");
    include("classes/profile.php");
    include("classes/notification.php");
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
</head>

<body class="container">
    <!-- top bar -->
    <br>
    <?php
    
    include("header.php");
    

    include_once("classes/image.php");
    
    
    $corner_image = "images/icons/user_male.jpg";
    
    if(isset($user_data)){
    
        $image_class = new Image();
        if(file_exists($user_data['profile_image'])){
            $corner_image =  $image_class->get_profile_thumb($user_data['profile_image']);
        }else
        if($user_data['gender'] == 'Female'){
            $corner_image =  "images/icons/user_female.jpg";
        }
        $special_char = false;
        if($user_data['verified'] == '1'){
            $special_char = true;
        }else{
            
            $special_char = true;
        }
    }    
    ?>

    <!-- cover area -->
    <div id="content">
       
        <!-- below cover -->

        <div id="content-holder">

            <!-- friends -->
            <div id="friend-content">
                <div id="friend-bar">
                    <img id="profile-picture" src="<?php echo $corner_image ?>" alt="">
                    <br>
                   <a style="text-decoration:none;color:white;font-weight:bold" href="profilepage.php"><?php echo $user_data['first_name'] . "<br>" . $user_data['last_name'] ?>
                   <?php
                if($special_char){
                    echo "<span><img style='width:15px' id='verify' src='images/icons/verify.svg' ></span>";
                }
                ?>
                </a> 
                </div>
            </div>

            <!-- post area -->
            <div id="post-content">
            <form method="post" enctype="multipart/form-data">
                <div id="post-one">
                
                    <textarea name="post" placeholder="What's on your mind?"></textarea>
                    <label id="upload-text" style="width:100px;" for="upload-button"><input type="file" name="file" id="upload-button" accept="image/jpeg">SELECT FILE</label>
                    <input name="post-button" type="submit" id="post-button" value="Post"><br>
                </div>
                </form>

                <!-- posts-->
                <div id="post-bar">

                    <?php

                    // $page_number = 1;
                    $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $page_number = ($page_number <= 0) ? 1 : $page_number;
                    $limit = 10;

                    
                    $offset = ($page_number - 1) * $limit;

                    
                    $DB =new Database();
                    $user_class = new User();

                    $followers = $user_class->get_following($_SESSION['apello_userid'], "user");
                    //echo $followers;
                    
                    $follower_ids = false;
                    if(is_array($followers)){

                        $follower_ids = array_column($followers, "userid");
                        $follower_ids = implode("','", $follower_ids);
                        
                    }
                    if($follower_ids){
                        $my_userid = $_SESSION['apello_userid'];
                        $sql = "SELECT * FROM posts WHERE userid = '$my_userid' || userid in('" . $follower_ids . "') ORDER BY DATE DESC LIMIT $limit offset $offset" ;
                        $posts = $DB->read($sql);
                    }

                    if ($posts){
                    foreach ($posts as $ROW){
                        $user = new User();
                        $row_USER = $user->get_user($ROW['userid']);

                        include("post.php");
                        }

                    }

                        //get current url
                        $pg = pagination_link();
                    ?>
                    <br>
                    <div style='display:flex'>
                    <a id='ref-page' href="<?= $pg['prev_page'] ?>">
                            <input id="page-button" style="flex:1;float:right;width:50%;margin-right:5px" value="Previous Page">
                        </a>
                        <a id='ref-page' href="<?= $pg['next_page'] ?>">
                            <input id="page-button" style="flex:1;float:left;width:50%;margin-left:5px" value="Next Page">
                        </a>
                        
                    </div>


                    </div>
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