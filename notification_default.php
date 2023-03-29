<?php
// echo "<pre>";
// print_r($NOTIS_ROW);
// die;
$type = $NOTIS_ROW['type'];
// echo $type . " " ;
$user_info = new User();
$string = '?';
if ($type == 'postupdate') {
    $string =  '※';
    $array_decode = json_decode($NOTIS_ROW['notis'], true);
    // print_r($array_decode);
    // die;
    if (!empty($array_decode)) {
        $notis_postid = $array_decode['postid'];
        $notis_userid = $array_decode['userid'];
    } else {
        $abort = true;
    }
} else
if ($type == 'profilechange') {
    $string = '⁑';
    $array_decode = json_decode($NOTIS_ROW['notis'], true);
    $notis_profile = $array_decode['image'];
    $notis_type = $array_decode['update_type'];
    $notis_profileid = $array_decode['userid'];
    $notis_userid = $notis_profileid;
    // echo  $notis_profileid;
    // die;
    $user_notis = $user_info->get_user($notis_profileid);
    // echo $user_notis;
    if ($user_notis['gender'] == "Male") {
        $notis_gender = 'his';
    } else {
        $notis_gender = 'her';
    }
    //
} else
if ($type == 'comment') {
    $string = '↪';
    $array_decode = json_decode($NOTIS_ROW['notis'], true);
    $notis_postid = $array_decode['postid'];
    $notis_userid = $array_decode['userid'];
    if (!empty($array_decode)) {
        // print_r($array_decode);
        $post_owner_id = $array_decode['postid_owner_id'];
        $notis_postid = $array_decode['postid'];
        $owner = new User();
        $personal_add = false;
        $replied = false;
        // echo $post_owner_id;
        $sql = "SELECT * FROM posts WHERE postid = '$post_owner_id'";
        // echo $sql;
        $DB = new Database();
        $result = $DB->read($sql);
        $my_post = false;
        // print_r($result);
        // die;
        $owner_name = $owner->get_user($result[0]['userid']);
        if ($result[0]['parent'] <> 0) {
            $replied = true;
            if ($result[0]['userid'] == $_SESSION['apello_userid']) {
                $my_post = true;
            }
        }
        
        $sql_2 = "SELECT userid FROM posts WHERE postid='$notis_postid' LIMIT 1";
        // echo $sql_2;
        $next = $DB->read($sql_2);
        $check_next = $next[0]['userid'];
        // echo $check_next;
        if($check_next == $result[0]['userid']){

            $personal_add = true;
        }

        if (($result[0]['userid'] == $_SESSION['apello_userid'])) {
            $my_post = true;
        }
        $replied_post_owner = false;
        $post_owner_name = $owner_name['first_name'];
        if ($notis_userid == $result[0]['userid']) {
            $replied_post_owner = true;
        }

        if ($owner_name['gender'] == "Male") {
            $replied_gender = 'his';
        } else {
            $replied_gender = 'her';
        }
    }
} else
if ($type  == "edited") {
    $string = '↺';
    $array_decode = json_decode($NOTIS_ROW['notis'], true);
    $notis_postid = $array_decode['postid'];
    $notis_userid = $array_decode['userid'];
} else
if ($type == "likedpost") {
    $string = '⇀';
    $array_decode = json_decode($NOTIS_ROW['notis'], true);
    $notis_postid = $array_decode['postid'];
    $owner_post_post_id = $array_decode['postid'];
    $notis_userid = $array_decode['userid'];
    $post_type = "comment";
    $sql = "SELECT * FROM posts WHERE postid = '$owner_post_post_id' LIMIT 1";
    $DB = new Database();
    $result = $DB->read($sql);
    if(!empty($result)){
        $users_owner = $result[0]['userid'];
        // print_r($result);
        if($users_owner == $_SESSION['apello_userid']){
            $like_my_post = true;
        }else{
            $like_my_post = false;
        }
    }
}else 
if($type == "status"){
    $string = '⨠';
    $array_decode = json_decode($NOTIS_ROW['notis'], true);
    $notis_postid = $array_decode['postid'];
    $notis_userid = $array_decode['userid'];


} else 
if ($type == "followed") {
    $string = '▧';
    $array_decode = json_decode($NOTIS_ROW['notis'], true);
    $notis_userid = $array_decode['userid'];
    // $notis_postid = $array_decode['postid'];
    $notis_follower_id = $array_decode['follower'];
    $follower_info = $user_info->get_user($notis_follower_id);
    $followed_full_name = $follower_info['first_name'] . " " . $follower_info['last_name'];
    // $owner_post_post_id = $array_decode['postid'];
}

$user_array = $user_info->get_user($notis_userid);
$abort = false;
$date = $NOTIS_ROW['date'];

$user_image = "images/icons/user_male.jpg";

if (isset($user_array)) {

    $image_class = new Image();
    $user_image =  $image_class->get_profile_thumb($user_array['profile_image']);
}


if (($type == 'profilechange') || ($type == 'profilechange')) {
}
// print_r($user_array);
// echo $type;
// echo "<br>";
$first_name = $user_array['first_name'];

if (isset($notis_userid) && ($notis_userid !== $_SESSION['apello_userid'])) {
    
    echo "<div id='notification'>
    <div id='type'>
    $string
    </div>";
    echo    "<div id='info'>";
    if ($type == 'postupdate') {
        echo "<span >$first_name made a new ";
        echo "<a href='apello.php?id=$notis_postid' id='underline'><span>post</span></a></span>";
    } else
            if ($type == 'comment') {

        if ($replied == false) {
            if ($replied_post_owner) {
                echo "<div><a style='text-decoration:none' href='profilepage.php?id=$user_array[userid]'>$first_name</a> commented on $replied_gender <a  id='underline' href='apello.php?id=$notis_postid'><span>post</span></a></div>";
            } else {
                if ($my_post) {
                    // echo "yes";
                    echo "<div><a style='text-decoration:none' href='profilepage.php?id=$user_array[userid]'>$first_name</a> commented on your <a href='apello.php?id=$notis_postid'><span id='underline'>post</span></a></div>";
                } else {

                    echo "<div><a style='text-decoration:none' href='profilepage.php?id=$user_array[userid]'>$first_name</a> commented on $post_owner_name's post</div>";
                }
            }
        } else
                    if ($replied == true) {

            if ($my_post) {
                echo "<div>$first_name replied to your <a href='apello.php?id=$notis_postid'><span>comment<span></a></div";
            } else {
                if($personal_add){
                echo "<div>$first_name added to $replied_gender <a href='apello.php?id=$notis_postid'><span>comment<span></a></div";
            }else{
                echo "<div>$first_name replied to a <a href='apello.php?id=$notis_postid'><span>comment<span></a></div";
            }
            }
        }
    } else
            if ($type == 'profilechange') {
        echo "<div>$first_name updated $notis_gender <a href='profilepage.php?id=$notis_userid'><span id=underline>$notis_type image</span></a> </div>";
    } else
            if ($type == 'followed') {

        echo "<div><a style='text-decoration:none' href='profilepage.php?id=$user_array[userid]'>$first_name</a> followed <a href='profilepage.php?id=$notis_follower_id'><span id='underline'>$followed_full_name</span></a></div>";
    }else
    if($type == "likedpost"){
        if($like_my_post){
            echo "<div>$first_name liked your <a id='underline' href='apello.php?id=$notis_postid'>$post_type</a></div>";

        }else{
            echo "<div>$first_name liked a <a id='underline' href='apello.php?id=$notis_postid'>$post_type</a></div>";

        }
    }else 
    if($type == "status"){
        //section=status&id=63220000737847460&postid=5932&counter=5&current=0
        echo "<div>$first_name uploaded a new <a id='underline' href='status.php?section=status&id=$notis_userid&postid=$notis_postid&counter=1&current=0'>status</a></div>";
    }
    echo        "<div><span id='date'>
            <br>" .
        Time::get_time($date) . "
        </span></div>
    </div>";
    echo "<div id='profile-image-post'>
                <img id='post_image' src='$user_image'>
            </div>";
    echo "</div>";

    if ($abort) {
        echo "<div id='no_notis' style='text-align:center'>
    No notifications at the moment
</div>";
    }
} else {
    $notis_counter--;
}
// echo "<div id='notice'>Showing latest ". $string_notis ."</div>";
//type for table: 
//    profilechange
//    postupdate
//    comment
//    edited
//    follower
//    likedpost
