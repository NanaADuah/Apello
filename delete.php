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
    $USER =$user_data;
    
        $error = "";

    if(isset($_GET['id'])){

        $Post = new Post();
        $row = $Post->get_one_post($_GET['id']);


        if(!$row){
            $error = "Post was not found.";
        }else{

            if($row['userid'] != $_SESSION['apello_userid']) {
                $error = "Access denied. You cannot delete this post";
            }


        }
    }else{
            $error = "Post was not found.";
    }
    
   

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $Post->delete_post($row['postid']);
        if(isset($S_SERVER['HTTP_REFERER']) /* && !strstr($_SERVER['HTTP_REFERER'], "delete.php") */){
            $return_to = $S_SERVER['HTTP_REFERER'];
            header("Location: " . $return_to);
     
         }else{
             header("Location: profilepage.php");

         }
        // header("Location:javascript://history.go(-2) ");
        die;
    }

    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete | Apello</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/delete.css">
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
    <div id="content" >

        <!-- below cover -->

        <div id="content-holder" > 
            <!-- post area -->
            <div id="post-content" >
                
                <form method="post" enctype="multipart/form-data">
                    <div id="post-one" >
                        <div id="del-heading"><h2 id="delete-post" style="text-align:center">Delete Post</h2></div>
                        
                        <form method="post" >
                            <div > 
                            <?php

                            if(!($error) == ""){
                                echo $error;
                            }else{
                               // echo "Are you sure you want to delete this post?";
                                $user = new User();
                                $row_USER = $user->get_user($row['userid']);
                               // include("post_delete.php");
                                
                            }
                             ?>
                            <span style="text-align:center;display:block;"><span id="sure_text"style="color:#405dbd;font-weight:600;">Are you sure you want to delete this post?</span><span><br><br>
                             <?php $image = $image_class->get_profile_thumb($user_data['profile_image']);?>
                             <div >  
                                <img id="del_image" src="<?php  echo $image ?>" alt="">
                                <span id="name_del"><?php echo $user_data['first_name'] . " ".$user_data['last_name'] ?></span>
                                <br>
                                <div id="text_del"><?php echo $row['post']; 
                                echo "<br><br>";
                                if($row['has_image'] == 1){
                                    if(file_exists($row['image'])){

                                    $post_class = new Image();    
                                    $post_image = $post_class->get_post_thumb($row['image']);
                                    echo "<div style='margin-left:5px;margin-top:opx'>";
                                    echo "<img id='delete_img' src='$post_image'  style='margin: 0;width:200px;' />";
                                    echo "</div>";
                                 } 


                                }

                                ?>
                                
                                <div style="text-align:center;margin-top:10px;" >
                                    <input name="post" type="submit" id="delete-button" value="Delete">
                                    <a href="profilepage.php"><input id="cancel-button" value="Cancel" ></a>    
                                </div>
                                
                                <br style="clear:both">
                                 
                                
                                    <input type="hidden" id="cancel-button" value="<?php echo $ROW['postid']?>">
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

</div>

</html>