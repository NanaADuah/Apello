<?php


//ini_set('session.cookie_lifetime', '864000');   //ten days in sesssion

session_start();

include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/settings.php");
include("classes/image.php");
include("classes/status.php");
include("classes/profile.php");
include("classes/notification.php");
include("classes/time.php");
include("classes/functions.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['apello_userid']);
$USER = $user_data;
// print_r($_SERVER);

$URL =  explode("/", $_SERVER['QUERY_STRING']);
// print_r($URL);
// die;
// require_once($URL[0]."php");
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $profile = new Profile();
    $profile_data = $profile->get_profile($_GET['id']);

    if (is_array($profile_data)) {
        $user_data = $profile_data[0];
    }
}
//posting
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['first_name'])) {

        $settings_class = new Settings();
        $settings_class->save_settings($_POST, $_SESSION['apello_userid']);
    } else {
        $post = new Post();
        $id = $_SESSION['apello_userid'];
        $result = $post->create_post($id, $_POST, $_FILES);
        if ($result == "") {
            header("Location: profilepage.php");
            die;
        } else {
            echo "<div style='text-align:center; font-size:12px;color:white;background-color:grey;'>";
            echo "<br>The following errors occurred:<br><br>";
            echo $result;
            echo "</div>";
        }
    }
}

//collect posts
$post = new Post();
$id = $user_data['userid'];
// $posts = $post->get_all_post($id);
$posts = $post->get_main_page_post($id);



//connect friends
$user = new User();
$friends = $user->get_friends($id);

$image_class = new Image();
?>


<!DOCTYPE html>
<html style="padding: none;" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profile | Apello</title>
    <link rel="stylesheet" href="css/profilepage.css">
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/post.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/dark.css">
</head>

<?php

$rating = new Post();

if (isset($USER['id']) && $USER['rating'] == "0") {
    $counter = rand(1, 15);
    // $counter =1;
    // echo ("<script> console.log('Random counter: " . $counter . "') </script>");
    $Get_user_likes = $user_data['likes'];

    if (($counter == 1) && $Get_user_likes >= 1) {
        echo
            "<div id='main'>
            <form method='get'> 
                <div id='options-div'>
                    <span id='heading_title'>Would you mind taking a moment to rate us?</span>
                    <div id='rating_box'>
                        <div class='box_text' id='1' value='1' name='rating' onclick='document.getElementById(1).style.background=\"#253c88\"; DisableRest(1)'><span>1</span></div>
                        <div class='box_text' id='2' value='2' name='rating' onclick='document.getElementById(2).style.background=\"#253c88\"; DisableRest(2)'><span>2</span></div>
                        <div class='box_text' id='3' value='3' name='rating' onclick='document.getElementById(3).style.background=\"#253c88\"; DisableRest(3)'><span>3</span></div>
                        <div class='box_text' id='4' value='4' name='rating' onclick='document.getElementById(4).style.background=\"#253c88\"; DisableRest(4)'><span>4</span></div>
                        <div class='box_text' id='5' value='5' name='rating' onclick='document.getElementById(5).style.background=\"#253c88\"; DisableRest(5)'><span>5</span></div>
                        <input type='hidden' name='data_rate' id='rating_data'>
                    </div>
                    <a><label style='width:100px' id='done' onclick='get_Selection()'>DONE</label></a>
                </div>
                <div class='thank-you' id='thank-you'>
                    <span id='thank-text'>Thank you<br> Enjoy using Apello!</span>
                </div>
            </form> 
    </div>

<script>

    var rating = 0;

   
    function DisableRest(id){
        var check;
        check = id;
        if(id=='1'){
            document.getElementById(2).style.background='#405dbd';
            document.getElementById(3).style.background='#405dbd';
            document.getElementById(4).style.background='#405dbd';
            document.getElementById(5).style.background='#405dbd';
            rating = '1';
        }else
        if(id=='2'){
            document.getElementById(1).style.background='#405dbd';
            document.getElementById(3).style.background='#405dbd';
            document.getElementById(4).style.background='#405dbd';
            document.getElementById(5).style.background='#405dbd';
            rating= '2';
        }else
        if(id=='3'){
            document.getElementById(1).style.background='#405dbd';
            document.getElementById(2).style.background='#405dbd';
            document.getElementById(4).style.background='#405dbd';
            document.getElementById(5).style.background='#405dbd';
        rating='3';
        }else
        if(id=='4'){
            document.getElementById(1).style.background='#405dbd';
            document.getElementById(2).style.background='#405dbd';
            document.getElementById(3).style.background='#405dbd';
            document.getElementById(5).style.background='#405dbd';
            rating='4';
        }else
        if(id=='5'){
            document.getElementById(1).style.background='#405dbd';
            document.getElementById(2).style.background='#405dbd';
            document.getElementById(3).style.background='#405dbd';
            document.getElementById(4).style.background='#405dbd';
            rating='5';
        }
    }
    
    function get_Selection(){
        document.getElementById(\"options-div\").style.display = \"none\";
        document.getElementById(\"thank-you\").style.display = \"block\";
        document.getElementById('rating_data').value = rating;
        console.log(rating);
        
        setTimeout(() => {
            document.getElementById('main').style.display=\"none\";
            document.getElementById('rating_data').click;
            window.location.href='profilepage.php?id=$user_data[userid]&data_rate='+rating;


        }, 2000);
       
    }   
</script>";
        
    }
}
?>

<body class="container">
    <!-- top bar -->
    <br>
    <?php

    include("header.php");
$my_likes = "";
if ($user_data['likes'] > 0) {
    if ($user_data['likes'] == 1) {
        $my_likes = "(" . $user_data['likes'] . " FOLLOWER)";
    } else {
        $my_likes = "(" . $user_data['likes'] . " FOLLOWERS)";
    }
}

$notis_check = new Notifications();
$unread = false;
$notis_class = new Notifications();
$followers = $notis_class->get_followers($_SESSION['apello_userid'], "user");
// echo $unread;
// print_r($followers);
// die;
$unread = $notis_check->unread_notis($followers, $_SESSION['apello_userid']);
// print_r($u   nread);
// echo "after";
// die;
if($unread){
    $notis_string = "Notifications •";
}else{
    $notis_string = "Notifications";
}

?>

    <!-- cover area -->
    <div id="content">
        <div id="bar-1">
            <?php

            $image = "images/icons/cover_image.jpg";
            if (file_exists($user_data['cover_image'])) {
                $image = $image_class->get_thumb_cover($user_data['cover_image']);
            } else {
                $image = "images/icons/cover_image.jpg";
            }

            ?>
            <img id="bar-image" src="<?php echo $image ?>" alt="">


            <span id="image_changes">
                <div id="profile_div">
                <div><a href="like.php?type=user&id=<?php echo $user_data['userid'] ?>"> <button style="min-height:10px;" type="button" id="like-button">FOLLOW <?php echo $my_likes ?></button></a></div>
            
                <?php

                $image = "images/icons/user_male.jpg";
                if ($user_data['gender'] == "Female") {
                    $image = "images/icons/user_female.jpg";
                }
                if (file_exists($user_data['profile_image'])) {
                    $image = $image_class->get_profile_thumb($user_data['profile_image']);
                }
                $special_char = false;
                $additional_char = "";
                if($user_data['verified']){
                    $special_char = true;
                    // $additional_char = "";
                }


                ?>
                
                
                <img  src="<?php echo $image  ?>" id="profile-2">
                <div id="profile-name"  style='-webkit-user-drag: none;'><span style='-webkit-user-drag: none;'>     <a id="name-span" href="profilepage.php?id=<?php echo $user_data['userid'] ?>"><?php echo $user_data['first_name'] . " " . $user_data['last_name'] ?>
                <?php
                if($special_char){
                    echo "<span><img style='width:15px' id='verify' src='images/icons/verify.svg' ></span>";
                }
                ?></a></span></div>
                
            </div>
            <?php

                $settings_class = new Settings();

                $settings = $settings_class->get_settings($_SESSION['apello_userid']);
                
                if(is_array($settings)){

                echo "<div id='bio-box' name='about' >@".  htmlspecialchars($user_data['username']) ."</div>";
                // echo "<br>";


                }

                
                echo "<div id=bio_string style='padding-left:10px;padding-right:10px;font-weight:normal;text-align:left;margin-bottom:5px'>$user_data[about]</div>";
                echo "<div style='text-align:left;margin-left:10px;font-size:12px'>";
                     if(isset($user_data['likes']))
                     {
                        echo $user_data['likes'] . " followers | 0 following  ";
                     }else
                     {
                         echo "0 followers";
                     }
                echo "</div>";
                if(isset($user_data['location']) && !empty($user_data['location'])){
                    echo "<div id=location_string>
                    <span id='location_text'><img id='location_img' src='images/icons/location.svg' style='width:13px;opacity:0.8'> ". htmlspecialchars($user_data['location']) . "</span>
                    </div>";
                    
                }

                // if(isset($user_data['likes']))
                // {
                //     echo "<div  style='margin:2px; float:left;'> " . $user_data["likes"] . "</div>";
                // }

                $join_date = 'October 2020';
                if(isset($user_data['date'])){
                    $datetime = new DateTime();
                    
                    // $join_date = $datetime->format('Y-m');
                    $join_date = date("F Y", strtotime($user_data['date'])) ;
                    // $join_date = $user_data['date'];
                    // $join_date = strtotime($user_data['date'],'YY MM DD');
                }
                echo "<div id='join_date'><img style='width:13px;opacity:0.75;filter:drop-shadow(0px 0.5px 1px black)' src='images/icons/calendar-light.svg' alt=''> Joined $join_date</div>";
                ?> 

            <!-- <br> -->

                <!-- <a id="change-profile-text" href="change-profile-image.php?change=profile">Change Profile Image</a> -->
                <!-- | -->
                <!-- <a id="change-profile-text" href="change-profile-image.php?change=cover">Change Profile Cover</a> -->
            </span>
            <!-- <br> -->
            

            
            <br style="clear:both;">
            <div id="options">
                <!-- <a href="profilepage.php?section=followers&id=<php echo $user_data['userid'] ?>"><div id="menu-buttons">Followers</div></a> -->
                <a href="profilepage.php?section=about&id=<?php echo $user_data['userid'] ?>"><div id="menu-buttons">About</div></a>
                <a href="profilepage.php?section=photos&id=<?php echo $user_data['userid'] ?>"><div id="menu-buttons">Photos</div></a>
                <?php
                if ($user_data['userid'] == $_SESSION['apello_userid']) {
                    echo '<a href="notifications.php?id='.$_SESSION['apello_userid'].'">' . '<div id="menu-buttons">'.$notis_string.'</div></a>';
                }
                ?>
                <a href="index.php"><div id="menu-buttons">Timeline</div></a>
                <?php
                if ($user_data['userid'] == $_SESSION['apello_userid']) {
                    echo '<a href="profilepage.php?section=settings&id=' . $user_data['userid'] . '">' . '<div id="menu-buttons">Settings</div></a>';
                }
                ?>
            </div>
        </div>
        <div id="status-view">

            <?php
        
            $user_image = "";
        
            if (isset($USER['profile_image']) && !empty($USER['profile_image'])) {

                $image_class = new Image();
                $corner_image =  $image_class->get_profile_thumb($user_data['profile_image']);
                $user_image =  $image_class->get_profile_thumb($USER['profile_image']);
            } else {
                if ($USER['gender' == 'Female']) {
                    $user_image = "images/icons/user_female.jpg";
                }else{
                    $user_image = "images/icons/user_male.jpg";

                }
            }

            ?>
                <div id="lock_my_status">
                    <div id="user_status">
                        <a id="stat_select" href="status.php?section=default&id=<?php echo $_SESSION['apello_userid'] ?>">
                            <div>
                                <div><img id="status_thumb" src="<?php echo $user_image ?>"></div>
                                <div id="thumb_name"><span id="user-span">My Status</span></div>
                                <br>
                            </div>

                        </a>
                    </div>
                </div>


                <?php
                $user_posts = [];

                $profile_status = new Status();

                $user_status = $profile_status->get_user_with_status();
                if ( !empty($user_status)) {

                    $USERS_STAT = array_unique($user_status, SORT_REGULAR);
                    $user_counter = -1;
                    // print_r($USERS_STAT);
                    if ($USERS_STAT) {
                        foreach ($USERS_STAT as $status) {
                            $user_counter++;
                            // echo $user_counter;
                            if (isset($USERS_STAT) && ($status['userid'] != $_SESSION['apello_userid'])) {
                                $s_posts = $USERS_STAT;
                                // print_r($status);

                                $STATUS_ROW = $user->get_user($status['userid']);
                                $s_use = new Status();

                                include("user_status.php");
                            }
                        }
                    }
                }else{
                    echo "<div id='no_stories'>No stories available at the moment</div>";
                }

                ?>


                <div style="width:10px;height:100%;">


                    <div style="width:10px">

                        <a id='d_select'>

                            <br>
                        </a>
                    </div>
            </div>
        </div>

        <!-- below cover -->

        <?php
        $section = "default";

        if (isset($_GET['section'])) {
            $section = $_GET['section'];
        }

        if ($section == "default") {
            include("profile_content_default.php");
        } else
                if ($section == "photos") {

            include("profile_content_photos.php");
        } else
                if ($section == "followers") {
            include("profile_content_followers.php");
        } else
                if ($section == "following") {
            include("profile_content_following.php");
        } else
                if ($section == "settings") {
            include("profile_content_settings.php");
        } else
                if ($section == "about") {
            include("profile_content_about.php");
        }


        ?>
    </div>


    <!-- <div style="text-transform:uppercase;width:100%;text-align:center;height:30px;"><span style="width:100px;background:#fff">Logout</span></div> -->



    <?php

    include("footer.php");
    if (isset($_GET['data_rate'])) {
        $rating = new Post();
        // die;
        $get_rating = $_GET['data_rate'];
        // $get_rating =  "<script>document.writeln(rating);</script>";
        // print_r($rating);
        // print_r($_COOKIE);
        // die;

        $rating->change_rating($USER['userid'], $get_rating);
    }

    
    ?>
</body>
</html>