<?php


session_start();
include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/settings.php");
include("classes/image.php");
include("classes/profile.php");
include("classes/time.php");
include("classes/status.php");
include("classes/notification.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['apello_userid']);
$USER = $user_data;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $profile = new Profile();
    $profile_data = $profile->get_profile($_GET['id']);

    if (is_array($profile_data)) {
        $user_data = $profile_data[0];
    }
}
//posting
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $post_status = new Status();
    $id = $_SESSION['apello_userid'];
    $result = $post_status->upload_status($id, $_POST, $_FILES);
    if ($result == "") {
        header("Location: status.php");
        die;
    } else {
        echo "<div style='text-align:center; font-size:12px;color:white;background-color:grey;'>";
        echo "<br>The following errors occurred:<br><br>";
        echo $result;
        echo "</div>";
    }
}


$status = new Status();
$user = new User();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stories | Apello</title>
    <link rel="stylesheet" href="css/status.css">
    <link rel="stylesheet" href="css/header.css">
    <!-- <link rel="stylesheet" href="css/master.css"> -->
    <link rel="stylesheet" href="css/footer.css">
</head>

<?php

$corner_image = "images/icons/user_male.jpg";


if (isset($user_data)) {

    $image_class = new Image();
    $corner_image =  $image_class->get_profile_thumb($user_data['profile_image']);
    $user_image =  $image_class->get_profile_thumb($USER['profile_image']);
} else {
    if ($USER['gender' == 'Female']) {
        $corner_image = "images/icons/user_male.jpg";
    }
}

?>

<body>

    <div id="main" class="status_container">
        <div id="story_heading"><span id="apello_stories">Apello Stories - Album</span><a href="profilepage.php" id="go_index">Home</a></div>
        <div id="holder" style="clear:both">
            <div id="status-view">
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

                $user_posts = $status->get_user_with_status();
                if (!empty($user_posts)) {
                    if (is_array($user_posts)) {

                        $user_ids = array_column($user_posts, "userid");
                        $user_ids = implode("','", $user_ids);
                    }

                    $USERS_STAT = array_unique($user_posts, SORT_REGULAR);
                    $user_counter = -1;
                    // print_r($USERS_STAT);
                    if ($USERS_STAT) {
                        foreach ($USERS_STAT as $status) {
                            $user_counter++;
                            // echo $user_counter;
                            if (isset($USERS_STAT) && ($status['userid'] != $_SESSION['apello_userid'])) {
                                $posts = $USERS_STAT;
                                // print_r($status);

                                $STATUS_ROW = $user->get_user($status['userid']);
                                $s_use = new Status();

                                include("user_status.php");
                            }
                        }
                    }
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

            <div id="status_page">
                <div id="timer_view">
                    <!-- <div id=timer_span></div> -->

                    <?php

                    if (!empty($user_posts)) {
                    }
                    if (isset($_GET['id']) && ($_GET['id'] != $_SESSION['apello_userid'])) {
                        // if($_GET['id'] != $_SESSION['apello_userid'] )
                        {
                            $status_count = new Status();
                            $count = $status_count->stat_number($_GET['id']);
                            for ($counter = 1; $counter <= $count['count']; $counter++) {
                                $div_counter = $counter;
                                echo "<div id=timer_span>
                                            <div  class='mover' id='timediv_$counter' style='display:none'></div>
                                        </div>";
                            }
                        }
                    }
                    ?>
                </div>
                <?php
                $section = "default";
                if (isset($_GET['section'])) {
                    $section = $_GET['section'];
                }

                if ($section == "status") {
                    include("statuses.php");
                } else
                if ($section == "default") {
                    include("personal_status.php");
                }
                // 
                //include("statuses.php");

                ?>

            </div>
        </div>

        <?php include("footer.php") ?>
</body>
<?php
if(isset($_GET['section'])){
if($_GET['section'] == 'status'){
echo "<script>
    var current_2 = 0;
    var d_counter = 0;
    var div_shower = 0;
    var div_2 = 0;
    var j_counter = -1;
    current_2 =  " . $_GET['current'] . " + 1;
    d_counter = ".$div_counter.";
    div = 'timediv_' + current_2;

    if (d_counter > 1) {
        j_counter = current_2-1;
        while (j_counter--) {
            div_shower = j_counter+1;
            div_2 = 'timediv_' + div_shower;
            document.getElementById(div_2).style.animation= 'none';
            document.getElementById(div_2).style.width = '100%';
            document.getElementById(div_2).style.display = 'block';
        }
    }

    function Next_Status() {
        var current = 0;
        var stat_id;
        var postid;
        var counter = 0;
        var status_type = 'default';
        next_check = false;
        var video_time = 30000;


        stat_id = ". $_GET['id'] .";
        other_id =".$user_data['userid'].";
        post_id = " .$_GET['postid'].";
        counter = $_GET[counter];
        // status_type = ". $_GET['st']. ";
        current = ". $_GET['current'] . "+ 1; 
        next_check = false;

        if (current == counter) {
            next_check = true;
            current = counter-1;
            
            if(current !== ''){
                current = current;
            }else{
                current = current-1;
            }
        }
        console.log(next_check)
        if(next_check == true){
        ref_string = 'status.php?section=default&id=$_SESSION[apello_userid]';
        }else{
        ref_string = 'status.php?section=status&id=$_GET[id]&postid=' + post_id + '&counter=' + counter + '&current=' + current;
        }
        window.location.href = ref_string;
    }

  
    // document.getElementById(div).style.width = '100%';
    document.getElementById(div).style.display = 'block';
    //'showTimeLeft 10s linear forwards';

    // document.getElementById(div).style.display = 'block';
    function showAnimation() {

    }

    {
    setTimeout(() => {
        Next_Status();
        }, 8000);
    }
</script>";
    }
}
?>

</html>