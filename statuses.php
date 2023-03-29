<?php
// error_reporting(0);
// echo "<pre>";
// print_r($USERS_STAT);
if (isset($_GET['id']) && $_SESSION['apello_userid'] != $_GET['id']) {
    $post_id = $_GET['id']; //$status['userid'];
    // echo $post_id;
    // die;
    $result = $more->get_latest_status($post_id);

    $image_class = new Image();
    $image = "images/icons/user_male.jpg";
    if ($user_data['gender'] == "Female") {

        $image = "images/icons/user_female.jpg";
    }
    if (file_exists($user_data['profile_image'])) {
        $image =  $image_class->get_profile_thumb($user_data['profile_image']);
    }

    $statuses = $s_use->get_user_statuses($status['userid']);
    // print_r($statuses);

    // foreach(){
    //     print_r($statuses);
    //     }
    $starter = 0;
    $counter = 0;
    $post_image = "";
    $status_count = new Status();
    $count = $status_count->stat_number($result[0]['userid']);
    $text = false;

    $counter = $count['count'];
    if (isset($_GET['current'])) {
        $starter = $_GET['current'];
        // $starter = 1;
    }

    if ($starter > $count['count']) {
        $starter = $count['count'] - 1;
    }

    if ($starter <= 0) {
        $starter = 0;
    }
    // echo ($starter);
    //  die;

    // print_r($starter);
    
    if(isset($result[0]['type'])){
        $status_type = $result[$starter]['type'];
        // echo "<br>" . $status_type;
    }

    $image_class = new Image();
    if (file_exists($result[$starter]['image'])) {
        $post_image = ($result[$starter]['image']);
    } else {
        // $post_image = "/images/sample.jpg";
        $post_image = "images/icons/status.png";
        $text = true;
    }
    $caption = $result[$starter]['caption'];
} else {
}
$current_id = $_GET['id'];
$current_id_2 = $_GET['id']."_1";
$counter = 0;
$status_count = new Status();
$counter = $status_count->stat_number($result[0]['userid']);
?>
<script>
    function Previous_Status() {
        var $current = 0;
        var $stat_id;
        var $postid;
        var $counter = 0;


        $stat_id = <?php echo "($_GET[id])" ?>;
        $other_id = <?php echo "($user_data[userid])" ?>;
        $post_id = <?php echo "($_GET[postid])" ?>;
        $counter = <?php echo "($_GET[counter])" ?>;
        $current = <?php if (isset($_GET['current'])) {
                        echo "($_GET[current])";
                    } ?> - 1;

        if ($current <= 0) {
            $current = 0;
        }

        $ref_string = "status.php?section=status&id=" + $stat_id + "&postid=" + $post_id + "&counter=" + $counter + "&current=" + $current + "&st=t";
        window.location.href = $ref_string;
    }
    
    function Next_Status() {
        var $current = 0;
        var $stat_id;
        var $postid;
        var $counter = 0;
        var $next_check = false;

        $stat_id = (<?php echo "($_GET[id])" ?>);
        $other_id = <?php echo "($user_data[userid])" ?>;
        $post_id = <?php echo "($_GET[postid])" ?>;
        $counter = <?php echo "($_GET[counter])" ?>;
        $current = <?php if(isset($_GET['current'])) {
                        echo "($_GET[current])";
                    }  ?> + 1;
        // console.log($stat_id);
        // $current += 1;
        // window.alert($current);
        //window.alert($current + " " + $stat_id + " " + $post_id + " " + $counter);
        if ($current == $counter) {
            
            $current = $counter-1;
                $next_check = false;
            
            if($current !== ""){
                $current = $current;

            }else{
                $current = $current-1;
                $next_check = true;
            }
        }
        // console.log($current + " " + $counter);
        // console.log($next_check);

        if($next_check == true){
            $ref_string = "status.php?section=default";
        }else{
        $ref_string = "status.php?section=status&id=" + <?php echo "($_GET[id])" ?> + "&postid=" + $post_id + "&counter=" + $counter + "&current=" + $current;
        }
        // console.log($ref_string);
        window.location.href = $ref_string;
    }

  
</script>

<?php
// echo $user_counter;
if ($_GET['id'] != $_SESSION['apello_userid']) {
    $result_id = $result[$starter]['userid'];
    $result_name = $user_data['first_name'] . " " . $user_data['last_name'];
    $has_image = $result[$starter]['has_image'];
    echo "<div id='detail_post'><img id='post_thumb' src=$image><div id='post_name'><a id='name_status' href='profilepage.php?id=$result_id'>$result_name</a>

        <div id='date'>" .  Time::get_time($result[$starter]['date']) . "</div>
    </div>
</div>
<br>";
/* if($status_type == "video"){
echo "<div id='status'  style='background:url($post_image) no-repeat center;background-size:cover;>
<div id='back_blur'>
        <div class='passer' id='pass_1' onclick='Previous_Status()'></div>
        <img id='stat_image'>
        <video id='status_vid'  height='400px' autoplay>
        <source src=$post_image  type='video/mp4'>
        </video>
        <div class='passer' id='pass_2' onclick='Next_Status()'></div>
	</div>
</div>";
}else */
if($text==false){
    echo "<div id='status' style='background:url($post_image) no-repeat center;background-size:cover;'>


    <div id='back_blur'>
        <div class='passer' id='pass_1' onclick='Previous_Status()'></div><img id='stat_image' src='$post_image'><div class='passer' id='pass_2' onclick='Next_Status()'></div>
	</div>
</div>
 



<br>";
}elseif($text == true){
    echo "
            <div id='text_blur'>
                <div class='text_' id='pass_1' onclick='Previous_Status()'></div>
                <div id='text_background'>
                    <div id='center_status'><span style='color:#dfdfeb'>$caption</span></div>
                </div>
                <div class='text_' id='pass_2' onclick='Next_Status()' on></div>
            </div>";
}

    if (!empty($caption) && ($text==false)){
        echo

            "<div id='caption-box'><span id='caption'>$caption<span></div>";
    }
} else {
}
if($text){
    echo "<script>
            var rgb = ['#792139', '#C1A03F', '#8FA842', '#A52C71','#1ed296','#243640','#6E257E', '#5697FF', '#7E90A4', '#736769', '#57C9FF', '#25C3DC', '#FF7B6C', '#55C265', '#FE8A8B', '#8C6991'];
            document.getElementById('text_blur').style.background = rgb[Math.floor(Math.random() * rgb.length)];
          </script>";
}

$view_id = $_GET['postid'];
$view = new Status();
$status_id = new Status();
$postid = $status_id->get_post_id($caption,$user_data['userid'],$result[$starter]['date']);
$result= $view->view_status($postid, $_SESSION['apello_userid']);
?>

</div>