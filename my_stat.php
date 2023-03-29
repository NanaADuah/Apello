<?php
// error_reporting(0);
// print_r($_statuses);
$captions = $_statuses['caption'];
$stat_date =  $_statuses['date'];
$stat_v = new Status();
$stat_views[0] = $stat_v->get_views($_statuses['postid']);
$view = $stat_views[0];

$last = $view['get'];
if(empty($last)){
    $last = 0;
}


// print_r($stat_views);
// die;

$post_id = $_statuses['postid'];
// $view =  $stat_views[0]['views'];

$text = true;
// echo $_statuses[0]['has_image'];

if (!empty($captions)) {
}

if ($_statuses['has_image'] == '1') {
    $post_class = new Image();
    // print_r($my_stat);

    $text = false;
    $post_image = $post_class->get_post_thumb($my_stat['image']);
} else {
    if ($_statuses['has_image'] == '0') {
        $text = true;
    }
    // $post_image = "";
    // "images/icons/cover_image.jpg";

}

$corner_image = "images/icons/user_male.jpg";

if (isset($user_data)) {

    $image_class = new Image();
    if (file_exists($user_data['profile_image'])) {
        $corner_image =  $image_class->get_profile_thumb($user_data['profile_image']);
    } else
        if ($user_data['gender'] == 'Female') {
        $corner_image =  "images/icons/user_female.jpg";
    } else {
    }
}
$Post = new Status();


if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stats = new Status();
    $result_ = $stats->get_postid_user($_GET['delete']);

    if ($result[0]['userid'] = $_SESSION['apello_userid']) {

        $Post->delete_user_status($_GET['delete']);
        echo "<script> window.location.href = 'status.php?section=default&id=$_SESSION[apello_userid]';</script>";
    }
}
?>
<?php

            $sql = "SELECT views FROM status WHERE userid = '$_statuses[postid]' LIMIT 1";
            $DB = new Database();
            $result = $DB->read($sql);
            if (is_array($result)) {
                $likes = json_decode($result[0]['likes'], true);
                $views = array_column($likes, "userid");
            }

echo "<div id='current_statuses'>
        <div id='profile-source'><div id='seperator'></div><img id='personal_img' src='$corner_image' alt='images/icons/user_male.jpg'></div>
        <div id='text-source'>
        <div id='caption_profile'><span>$captions<span></div>
        <a href='views.php?id=$post_id' id='views_tag' style='text-decoration:none'><div id='views'>Views: $last</div></a>
        <div id='stat_date'> " . Time::get_time($my_stat['date']) . "</div>
        </div>";

if (!$text) {
    echo "<div id='img-source' style='background: url($post_image) no-repeat center;background-size:cover;'><div id='self_stat_image'><img id='img-status' src='$post_image' alt=''></div></div>";
}

echo "<form method='DELETE' action='status.php?section=default&id=$_SESSION[apello_userid]'>    
            <div onclick='delete_status($post_id)'><button id='settings' type='submit'>X</button></div>
            <input type='hidden' value=$post_id name='delete'>
            </form>
    </div>";
$Post = new Status;
?>
<script>
    function delete_status($id) {

        var $stuff;
        var $result;

        $stat_id = <?php echo "$post_id" ?>;

    }
</script>