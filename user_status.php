<!-- <script type="text/javascript" src="jquery/jquery-3.5.1.min.js"></script> -->
<?php
$post_id = $status['userid'];
error_reporting(0);

// echo $post_id;
// print_r($STATUS_ROW);
// die;
// if(isset($_GET['id'])){
//     $post_id = ($_GET['id']);
// }
// echo $user_counter;
// die;
$more = new Status();

if(isset($_GET['id'])){
    $get_id = $_GET['id'];
}

$result = $more->get_latest_status($post_id);

if (isset($user_data) ) {
    $image_class = new Image();
    $corner_image =  $image_class->get_profile_thumb($STATUS_ROW['profile_image']);
}

if(isset($_GET['counter'])){
    $interval = $_GET['counter'];
    $type_decode = $result[$interval]['type'];
}

if (isset($_GET['postid'])){

    $postid__ = $result[0]['postid'];
    $counter = 1;

    $status_count = new Status();
    $count = $status_count->stat_number($result[0]['userid']);   
    $counter = $count['count'];

    $current = 0;
    
    // die;

    {

    echo
        "<div id='user_status'>
        
        <a id='stat_select' href='status.php?section=status&id=$post_id&postid=$postid__&counter=$counter'>
                <div><img id='status_thumb' src=$corner_image>
            </div><div id='thumb_name'>$STATUS_ROW[first_name] $STATUS_ROW[last_name]</div>
                <br>
        </a>
    </div>  ";
    }
}else{
     if(isset($_GET['id']) && ($_GET['id'] != $_SESSION['apello_userid']))
     {
        $postid__ = $result[0]['postid'];
        $counter = 0;
        $status_count = new Status();
        $counter = $status_count->stat_number($result[0]['userid']);

        echo
            "<div id='user_status'>
            
            <a id='stat_select' href='status.php?section=status&id=$post_id&postid=$postid__&counter=$counter'>
                    <div><img id='status_thumb' src=$corner_image>
                </div><div id='thumb_name'>$STATUS_ROW[first_name] $STATUS_ROW[last_name]   </div>
                    <br>
            </a>
        </div>";
        }else{
            $postid__ = $result[0]['postid'];
            $counter = 1;
            $status_count = new Status();
            $counter = $status_count->stat_number($result[0]['userid']);
            // echo $postid__;
            echo
                "<div id='user_status'>
                
                <a id='stat_select' href='status.php?section=status&id=$post_id&postid=$postid__&counter=$counter[count]'>
                        <div><img id='status_thumb' src=$corner_image>
                    </div><div id='thumb_name'>$STATUS_ROW[first_name] $STATUS_ROW[last_name] </div>
                        <br>
                </a>
            </div>  ";  
        }   
}
// else{
//     echo "oops";
//     header("status.php?section=default");
//}
?>
