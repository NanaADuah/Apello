<?php

// error_reporting(0);
session_start();
include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/notification.php");
include("classes/post.php");
include("classes/settings.php");
include("classes/image.php");
include("classes/profile.php");
include("classes/time.php");
include("classes/status.php");

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

$name  = new User();
$personal_name = $name->get_user($_SESSION['apello_userid']);
$first_name =$personal_name['first_name'];
$last_name = $personal_name['last_name'];
$username = $first_name . " ". $last_name;
$notifications = [];

$notis = new Notifications();
$notifications = $notis->get_notifications();
// echo "<pre>";
// print_r($notifications);
// die;        
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | Apello</title>
    <link rel="stylesheet" href="css/notifications.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<?php
    include("header.php");
    
?>

<body class="container">

    <div id="main">
        <div id='notis_name'>Notifications - <?php echo $username ?></div>
        <?php  
            $notis_counter = 0;
            $string_notis = "";

            // echo "<pre>";
            // print_r($notifications);
            // die;
            if(!empty($notifications)){
            foreach($notifications as $NOTIS_ROW){
                $notis_counter++;
                // $post_id_owner = $NOTIS_ROW['userid'];
                // echo $post_id_owner;
                
                // if(!$post_id_owner == $_SESSION['apello_userid'])
                {

                    include("notification_default.php");
                }

            }
            if($notis_counter == 1){
                $string_notis= "notification";
            }else{
                $string_notis= $notis_counter." notifications";
            }
            echo "<div id='notice'>Showing latest ". $string_notis ."</div>";
        }else{
            echo "<div id='no_notis' style='text-align:center'>
                No notifications at the moment
            </div>";
            
        }
        echo "<div id='clear_button'>Clear</div>";
        ?>
    </div>
        

        <?php include("footer.php") ?>
</body>

</html>