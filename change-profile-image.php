<?php
    
    session_start();
    include("classes/connect.php");
    include("classes/login.php");
    include("classes/user.php");
    include("classes/notification.php");
    include("classes/post.php");
    include("classes/image.php");

    $login = new Login();
    $user_data = $login -> check_login($_SESSION['apello_userid']);
    $USER = $user_data;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
   
        if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != ""){ 
        
        if($_FILES['file']['type'] == "image/jpeg" /*  || $_FILES['file']['type'] == "image/png" */){

            $allowed_size = 5 * (1024 * 1024);
            if($_FILES['file']['size'] <= $allowed_size){

                //everything is fine
                $folder = "uploads/" . $user_data['userid'] . "/";

                //create folder
                if (!file_exists($folder)){
                        mkdir($folder, 0755, true);
                }
                $image_class = new Image();


                //if($_FILES['file']['type'] == "image/png"){
                $filename = $folder . $image_class->generate_filename(15) . ".jpg";
            // }else{
            //     $filename = $folder . $image_class->generate_filename(15);
            // }
            
                 move_uploaded_file($_FILES['file']['tmp_name'], $filename);
                
                    //not supported anymore
                if($_FILES['file']['type'] == "image/png"){
                    $image = imagecreatefrompng($filename);
                    $bg = imagecreatetruecolor(imagesx($image),imagesy($image));
                    imagefill($bg, 0,0, imagecolorallocate($bg, 255, 255, 255));
                    imagecopy($bg, $image, 0,0,0,0, imagesy($image),imagesy($image));
                    imagealphablending($bg, TRUE);
                    imagedestroy($image);
                    $quality = 50;
                    $final = imagejpeg($bg, $filename . ".jpg", $quality);
                    imagedestroy($bg);
                }

                //////////////////////////////
                $change = "profile";

                    if(isset($_GET['change'])){
                        $change = $_GET['change'];
                    }                

                if($change=="cover"){
                    if(file_exists($user_data['cover_image'])){
                        unlink($user_data['cover_image']);
                        if(file_exists($user_data['cover_image']) . "_cover_thumb.jpg"){
                        // unlink($user_data['cover_image'] . "_cover_thumb.jpg");
                    }
                    }
                    $image_class->resize_image($filename, $filename, 1500, 1500);
                 }
                else{
                    if(file_exists($user_data['profile_image'])){
                        unlink($user_data['profile_image']);
                    }   
                    $image_class->resize_image($filename, $filename, 1500, 1500);
                }

                if(file_exists($filename)){
        
                    $userid = $user_data['userid'];

                    if($change == "cover"){
                        $query = "UPDATE users SET cover_image  = '$filename' WHERE userid = '$userid' LIMIT 1";    
                        $_POST['is_cover_image'] = 1;
                        
                    }else{
                       $query = "UPDATE users SET profile_image = '$filename' WHERE userid = '$userid' LIMIT 1";
                       $_POST['is_profile_image'] = 1;
                    }

                    $DB = new Database();
                    $DB->save($query);
                    
                    //create a post
                    $post = new Post();
                    $post->create_post($userid, $_POST, $filename);
                    

                    header("Location: profilepage.php");
                    die;
                    }

            }else{
                echo "<div style='text-align:center; font-size:12px;color:white;background-color:grey;'>";
                echo "<br>The following errors occurred:<br><br>";
                echo "Image size is too large!";
                echo "</div>";
            }
        }else{
            echo "<div style='text-align:center; font-size:12px;color:white;background-color:grey;'>";
            echo "<br>The following errors occurred:<br><br>";
            echo "Only images of type jpeg are allowed";
            echo "</div>";
        }
    }else   {
            echo "<div style='text-align:center; font-size:12px;color:white;background-color:grey;'>";
            echo "<br>The following errors occurred:<br><br>";
            echo "Please add a valid image...";
            echo "</div>";

        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Profile Image | Apello</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <!-- top bar -->
    <br>
    <?php
    
    include("header.php");
    ?>

    <!-- cover area -->
    <div id="content">

        <!-- below cover -->

        <div id="content-holder">


            <!-- post area -->
            <div id="post-content">

                <form method="post" enctype="multipart/form-data">
                    <div id="post-one">
                        <input id="upload-button" name="file" type="file"  accept="image/jpeg" onchange="document.getElementById('upload-text').innerHTML = 'File selected...'">
                        <label for="upload-button" id="upload-text" >SELECT FILE</label>
                        <input type="submit" id="post-button" value="Change"><br>
                        <div id="pic_div" style="text-align:center">
                        <?php 

                        //check for mode
                         if(isset($_GET['change']) && $_GET['change'] == "cover"){

                             $change = "cover"; 
                             echo "<img id='change-images' src='$user_data[cover_image]' style='margin-top:10px;max-width:500px'> ";
                    }else{
                             echo "<img id='change-images' src='$user_data[profile_image]' style='margin-top:10px;max-width:500px'> ";
                    }

                        ?> 
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


</body>

</div>

</html>