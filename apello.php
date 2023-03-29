<?php
    
    session_start();
    include("classes/connect.php");
    include("classes/login.php");
    include("classes/user.php");
    include("classes/post.php");
    include("classes/image.php");
    include("classes/notification.php");
    include("classes/profile.php");
    include("classes/functions.php");
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
        // header("Location: single_post.php?id=$_GET[id]");
        header("Location: apello.php?id=$_GET[id]");
        die;
    }else{
        echo "<div style='text-align:center; font-size:12px;color:white;background-color:grey;'>";
        echo "<br>The following errors occurred:<br><br>";
        echo $result;
        echo "</div>";
    }



} 

        
    $Post = new Post;
    $error = "";
    $ROW = false;

    if(isset($_GET['id'])){
        
        $ROW = $Post->get_one_post($_GET['id']);
        }else{
            $error = "No post was found";
    }
    
       
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Comments | Apello</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/single_post.css">
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/footer.css">
 </head>

<body class="single_container">
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
            <div id="single-post-content">
                
                <form method="post" enctype="multipart/form-data">
                    <div id="post-one">

                        <?php
                        if(isset($_GET['id'])){
                            $DB = new Database();
                            $check = $_GET['id'];
                             
                            $sql = "SELECT * FROM posts WHERE postid = '$check' LIMIT 1";
                            $result = $DB->read($sql);
                            // print_r($result);
                            // echo $sql;
                           if(!empty($result)){

                            $image_class = new Image();
                            if(is_array($ROW)){
                                $user = new User();
                                $row_USER = $user->get_user($ROW['userid']);
                                include('post.php');
                            }

                        echo "<br style='clear:both'>";
                        
                            
                            
                            //    echo "not";
                            //    echo "not";
                            //    die;
                               echo "<div id='post-area' style='margin-bottom:5px'>
                                <form method='post' enctype='multipart/form-data'>
                                    <textarea name='post' placeholder='Post a comment...' style='margin-bottom:5px'></textarea>
                                    <label id='upload-text' style='width:100px;' for='upload-button'><input style='visibility: hidden;' type='file' name='file' id='upload-button' value='Select file' accept='image/jpeg'>Upload file</label>
                                    <input type='hidden' name='parent' value='$ROW[postid]'>
                                    <input type='submit' id='post-button' value='Post'>
                                        
                                    <br>
                                </form>
                            </div>";



                            
                            
                            // $sql = "SELECT * FROM posts WHERE postid = '$_GET[id]' LIMIT 1";
                            // echo $sql;
                            // $result = $DB->read($sql);
                            // print_r($result);
                            
                            // if(!empty($comments))
                            // {
                            $user = new User();
                            $comment_user = new User();
                            
                            
                            
                            $comm = new Post();
                            // echo "Nana";
                            $comment_count[0] = 0;
                            $comments = $comm->get_comments($ROW['postid']);
                            $comment_count = $comm->get_comment_count($ROW['postid']);
                            
                            if(is_array($comments)){
                                foreach ($comments as $COMMENT){
                                     $row_USER = $user->get_user($COMMENT['userid']);
                                    include("comment.php");
                                    }
                                }
                            // }
                        }else{
                            $sql = "SELECT * FROM posts WHERE parent = '$check' LIMIT 1";
                            $result = $DB->read($sql);
                            // print_r($result);
                            if(empty($result)){
                            }else{
                                echo "<div style='background:white;opacity:0.5;padding:10px;text-align:center;border-radius:10px;'>This post has been deleted!</div>"; 
                            }
                        }
                    }

                    $pg = pagination_link();

                    ?>

                    </div>
                    <?php
                        // print_r($comments) ;

                    if($comment_count[0]['count'] > 5){
                    
                        echo "<div style='display:flex'>
                        <a id='ref-page' href='$pg[prev_page]'>
                          <input id='page-button' style='flex:5;float:right;width:50%;margin-right:5px' value='Page'>
                        </a>
                        <a id='ref-page' href='$pg[next_page]'>
                          <input id='page-button' style='flex:1;float:left;width:50%;margin-left:5px' value='Next Page'>
                        </a>
                          <br>
                          <br>
                      </div>";
                    }
                    ?>
                </form>

            </div>
        </div>
        <?php
        include("footer.php");
        ?>
    </div>

</body>

</div>

</html>