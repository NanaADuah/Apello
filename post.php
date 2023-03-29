<div id="post_div" style="opacity:0;width:100%;height:5px;padding:0;margin:0;"> </div>
<div id="post" style="overflow:hidden">
<?php
// print_r($ROW);
// error_reporting(0);
$deleted_parent = false;

$_sql = "SELECT parent FROM posts WHERE postid = '$ROW[postid]' LIMIT 1";
$DB = new Database();
$_result = $DB->read($_sql);
$the_result = $_result[0]['parent'];
if(!$the_result == '0'){
// echo $the_result;
$_sql2 = "SELECT * FROM posts WHERE postid = '$the_result' LIMIT 1";
$_result2 = $DB->read($_sql2);
// print_r($_result2);
// die;
// print_r($_result2);
if(empty($_result2)){
    $deleted_parent = true;
}else{
    $deleted_parent = false;
}
}else{
    $deleted_parent = false;
}
$stat_check = "SELECT * FROM status WHERE userid = $ROW[userid]";
$DB = new Database();
$stat_result = $DB->read($stat_check);
if(!empty($stat_result)){
    $has_status = true;
}else{
    
    $has_status = false;
}
?>

            <div>
            <?php           
        // error_reporting(0);
        
        $image = "images/icons/user_male.jpg";

        if ($row_USER['gender'] == "Female") {

            $image = "images/icons/user_female.jpg";
        }

        if (file_exists($row_USER['profile_image'])) {
            $image =  $image_class->get_profile_thumb($row_USER['profile_image']);
        }
        if($deleted_parent){
            echo "<br>";
        }
        if($has_status){
            echo
            "<img id='user-photo' style='border: 2px solid #DFDFEB;' src='$image'>";}
            else{
            echo    "<img id='user-photo' src='$image'>";
            }
        ?>
        
    </div>

    <div id="post_main">
        <?php
        if($deleted_parent){
            echo "<div id='deleted_post'>
            ? - Original post was deleted
            </div>";
            // echo "<br>";
        }

        if(!$deleted_parent){
            $DB = new Database();
        // print_r($ROW);
        if (!$ROW['parent'] == 0) {
            $original_post_id = $ROW['parent'];
            // echo $original_post_id;
            // $sql = "SELECT * FROM posts WHERE ";
            // $result= $DB->read($sql);

            $your_pronoun = false;
            $owner = new Post();
            $post_owner_id = $owner->get_one_post($original_post_id);
            // print_r($post_owner_id);

            $sql = "SELECT userid FROM posts WHERE postid = '$post_owner_id[postid]' LIMIT 1";
            $userpost_info = $DB->read($sql);

            $owner_post = $userpost_info[0]['userid'];
            $uuser = new User();
            $user_comment = $uuser->get_user($owner_post);
            $first_name = $user_comment['first_name'];

            $comment_post_name =  $first_name;
            $DB = new Database();
            // print_r($ROW);
            // echo $owner_post;

            
            if($owner_post == $_SESSION['apello_userid']){
                $comment_string = 'you commented on ' ;
                $own_reply;
                $your_pronoun = false;

            }else{
                $comment_string = 'commented on ';
            }


            $DB = new Database();
            $sql = "SELECT userid FROM posts WHERE postid = '$original_post_id' LIMIT 1";
            // echo $sql;
            // die;
            $result = $DB->read($sql);
            // echo $result[0]['userid'];
            $own_reply = false;
            $other_id = $result[0]['userid'];
            // echo $owner_post;
            // echo '<br>';
            // echo $other_id;
            $post_type = "post";
            $sql_2 = "SELECT userid, parent FROM posts WHERE postid = '$original_post_id' LIMIT 1";
            $result = $DB->read($sql_2) ;

            if ($owner_post == $other_id) {
                if ($owner_post !== $_SESSION['apello_userid']) {
                    $your_pronoun = true;
                }
                $owner_gender = $row_USER['gender'];
                $post_owner = false;
                if ($owner_post == $_SESSION['apello_userid']) {

                    $post_owner = true;
                }

                if ($owner_gender == "Male" && $post_owner == false) {
                    $pronoun_2 = 'his';
                } else
                if ($owner_gender == "Female" && $post_owner == false) {
                    $pronoun_2 = 'her';
                }  else
                if ($post_owner) {
                    $pronoun_2 = 'your';
                } 
            }
            echo "<div>";
            echo $ROW['parent'];
            echo "</div>";
            if ($ROW['parent'] <> 0) {
                $post_type = "comment";
                // print_r($ROW);
                $sql = "SELECT * FROM posts WHERE postid = $ROW[parent] LIMIT 1";
                $DB = new Database();
                $result_userid =  $DB->read($sql);
                $resultid = $result_userid[0]['userid'];
                $new_2 = new User();
                $user_result_date = $new_2->get_user($resultid);
                if ($ROW['userid'] != $_SESSION['apello_userid'] ) {
                if(!($resultid == $_SESSION['apello_userid'])){
                    $comment_string = "replied under ";
                    $to_my_reply= false;
                    $name_user = $user_result_date['first_name'];
                }else
                {
                    $comment_string = "replied under ";
                    $pronoun_3 = "your";
                    $to_my_reply = true;
                }
            }else{
                echo $resultid;
                if(($resultid == $_SESSION['apello_userid'])){
                    $comment_string = "you replied under ";
                    $to_my_reply= true;
                    $name_user = $user_result_date['first_name'];
                }else{
                    $comment_string = "replied under ";
                    $pronoun_3 = "their";
                    $to_my_reply = false;
                    $name_user = $pronoun_3;
                    // echo "yeet";
                }
                }
            }

            if ($ROW['userid'] == $_SESSION['apello_userid'] ) {
                $your_pronoun = true;
                // echo $owner_post;
                // echo $ROW['userid'];
                $pronoun_2 = 'your';
                if($result[0]['parent'] <> 0){
                $comment_string = "you replied under ";
                }
            } else {
                $your_pronoun = false;
            }

            $owner_id = $user_comment['userid'];
            if($ROW['userid'] ==$owner_id){
                $own_reply = true;
                if($user_comment['gender'] == "Male"){
                    $owner_gender = "his";
                } else{
                    $owner_gender = "her";
                }
                // print_r($user_comment);
            }
        }

}
   
        ?>
        <div><span><a id="user-name" href="profilepage.php?id=<?php echo $ROW['userid'] ?>" style="text-decoration:none"><?php echo htmlspecialchars($row_USER['first_name']) . " " . htmlspecialchars($row_USER['last_name']) ?>
        <?php
        $special_char = false;   
        $sql = "SELECT verified FROM users WHERE userid = '$ROW[userid]' LIMIT 1";
        $DB = new Database();
        $result = $DB->read($sql);
        if($result){
        if($result[0]['verified'] == '1'){
            $special_char = true;
        }
                if($special_char){
                    echo "<span><img style='width:10px' id='verify' src='images/icons/verify.svg' ></span>";
                }
            }
                ?>
    </a>
    <?php
    $sql = "SELECT username FROM users WHERE userid = '$ROW[userid]' LIMIT 1";
    $DB = new Database;
    $user_first_name = $DB->read($sql);
    $username = $user_first_name[0]['username'];
    ?>
    <a id="username-link" > @<?php echo $username ?> </a>
     · <span id="date-post"> <?php echo Time::get_time($ROW['date']) ?></span> </span>
            <?php
            // print_r($ROW);
            $DB = new Database();
            // $sql = "SELECT * FROM posts WHERE id '$original_post_id' LIMIT 1";
            
            if(!$deleted_parent){
            if(!empty($result)){
            if (!$ROW['parent'] == 0) {

                if ($your_pronoun == true) {
                    // echo "<div id='comment_under'><a><span style='color: #999'>&nbsp $comment_string</span></a><a  href='apello.php?id=$original_post_id' style='font-weight:400;text-decoration:underline;color:#888'> $name_user's $post_type</a></div>";
                    echo "<div id='comment_under'><a><span style='color: #999'>&nbsp $comment_string</span></a><a  href='apello.php?id=$original_post_id' style='font-weight:400;text-decoration:underline;color:#888'> your $post_type</a></div>";
                } else {
                    if($own_reply == true){
                        echo "<div id='comment_under'><a><span style='color: #999'>&nbsp $comment_string</span></a><a  href='apello.php?id=$original_post_id' style='font-weight:400;text-decoration:underline;color:#888'> $owner_gender $post_type</a></div>";
                    }else{
                        if($to_my_reply){

                            echo  "<div id='comment_under'><a><span style='color: #999'>&nbsp $comment_string</span></a><a  href='apello.php?id=$original_post_id' style='font-weight:400;text-decoration:underline;color:#888'> $pronoun_2 $post_type</a></div>";
                        }else{

                            echo "<div id='comment_under'><a><span style='color: #999'>&nbsp $comment_string</span></a><a  href='apello.php?id=$original_post_id' style='font-weight:400;text-decoration:underline;color:#888'> $comment_post_name's $post_type</a></div>";
                        }
                    }
                }
            }
        }
    }
            ?>
            <span id='user-updates'>
                <?php
                if ($ROW['is_profile_image']) {

                    $pronoun = 'his';
                    if ($row_USER['gender'] == "Female") {
                        $pronoun = "her";
                    }
                    echo "<span id='user-updates'> - updated $pronoun profile image</span>";
                } else
            if ($ROW['is_cover_image']) {
                    $pronoun = 'his';
                    if ($row_USER['gender'] == "Female") {
                        $pronoun = "her";
                    }
                    echo "<span id='user-updates'> - updated $pronoun cover image</span>";
                }

                ?>
            </span>
        </div>

        <div id="text_post"><span style="overflow-y:scroll"><?php echo (check_tags($ROW['post'])) ?></span></div>
        <br>
        <div>
            <?php
            if (file_exists($ROW['image'])) {
                $post_image = $image_class->get_post_thumb($ROW['image']);
                echo "<img  id='post-official-image' src='$post_image' style='width:80%; border-radius: 2px;' >";
            }
            ?>
        </div>
        <br id="break-tag-post">
        <br style="clear:both">
        <?php

        $likes = "";

        $likes = ($ROW['likes'] > 0) ? $ROW['likes'] : "";
        ?>
        <?php

        $comments = "";

        if ($ROW['comments'] > 0) {
            $comments =  $ROW['comments'];
        }

        ?>
        <?php
        $last_location = "<script> window.alert(window.location.href)</script>";
      
        $_SESSION['return_to'] = $last_location;
        // die;
        // Like<?php echo $likes
        ?>
        <div id='bottom-post'>
            <a onclick="like_post(event)" id="like-tag" href="like.php?type=post&id=<?= $ROW['postid'] ?>"><img id="like_svg" src="images/icons/like-light.svg"><?= $likes ?></a>
            <a id="additional_tag" href="apello.php?id=<?php echo $ROW['postid'] ?>"><span><img id="comment_svg" src="images/icons/comment-light.svg" alt=""> <?php echo $comments ?></span></a>

            <?php

            if ($ROW['has_image']) {

                echo "<a id='view-image' href='image_view.php?id=$ROW[postid]' style='color:#999;text-decoration:none'>";
                // echo " . View Full Image";
                echo "<img id='like_svg' src='images/icons/expand-light.svg' alt=''>";
                echo "</a>";
            }

            ?>

            <br id="mobile_break">
            <span id="additional_tag" style="color:#999;font-size:0.8em">
                <?php

                $post = new Post();
                if ($post->i_own_post($ROW['postid'], $_SESSION['apello_userid'])) {
                    // echo " | ";
                    echo "<a id='additional_tag' href='edit.php?id=$ROW[postid]' style='text-decoration:none;color:#bbb'>
                    <img id='like_svg' src='images/icons/edit-light.svg' alt=''>
                    </a>
                    
                    <a id='additional_tag' href='delete.php?id=$ROW[postid]' style='text-decoration:none;color:#bbb'>
                    <img id='like_svg' src='images/icons/delete-light.svg' alt=''>
                    </a>
                    
                    ";
                }

                $sql = "SELECT likes FROM likes WHERE type = 'post' && contentid = '$ROW[postid]' LIMIT 1";
                $DB = new Database();
                $result = $DB->read($sql);
                $i_liked = false;

                if (is_array($result)) {
                    $likes = json_decode($result[0]['likes'], true);
                    $user_ids = array_column($likes, "userid");

                    if (isset($_SESSION['apello_userid'])) {

                        if (in_array($_SESSION['apello_userid'], $user_ids)) {
                            $i_liked = true;
                        }

                        if ($ROW['likes'] > 0) {
                            echo "<br>";
                            echo "<a style='text-decoration:none; color:#999;' href='likes.php?type=post&id=$ROW[postid]' >";

                            if ($ROW['likes'] == 1) {

                                if ($i_liked) {
                                    echo "You liked this post";
                                } else {
                                    if (!$i_liked) {
                                        echo "1 person liked this post";
                                    }
                                }
                            } else {

                                if ($ROW['likes'] > 1) {
                                    if ($i_liked) {


                                        if ($i_liked && $ROW['likes'] == 2) {
                                            echo "You and 1 person liked this";
                                        } else {
                                            $_user_likes = $ROW['likes'] - 1;
                                            echo "You and " . $_user_likes . " others liked this";
                                        }
                                    } else {

                                        echo $ROW['likes']  . " people liked this post";
                                    }
                                }
                            }

                            echo "</a>";
                        }
                    }
                }



                ?>
        </div>
        </span>
    </div>
</div>
<script type="text/javascript">

function like_post(e){
    e.preventDefault();
    post_link = e.target.parentElement.href;
    var data = {};
    data.link = post_link;
    data.action = "like_post";
    ajax_send(data, e.target.parentElement);
    console.log(e);
    
}

function ajax_send(data, element){

    var ajax = new XMLHttpRequest();


    ajax.addEventListener('readystatechange', function(){
    if(ajax.readyState == 4 && ajax.status == 200){
        
        response(ajax.responseText,element);

    }
    });

    
    data = JSON.stringify(data);  

    ajax.open("post", "ajax.php", true);
    ajax.send(data);

}

function response(result, element){
    if(result != ""){
        alert(result);
        var obj = JSON.parse(result);

        if(typeof obj.action != "undefined"){

            if(obj.action = "like_post"){
                var like_s = "";
                // console.log(obj.likes + " <- likes");

                
                if(parseInt(obj.likes) > 0){

                    like_s = obj.likes;
                }else{
                    like_s = obj.likes;
                }
                element.innerHTML = like_s;

            }
        }

    }
    }
</script>