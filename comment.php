<div id="post_div" style="width:100%;height:5px;padding:0;margin:0;"> </div>
<?php

// print_r($COMMENT);
// die;    
?>
<div style="display:block;padding:4px">
<div id="comment_id_photo">
    <?php
    $image = "images/icons/user_male.jpg";
    //  $row_USER = $user->get_user($ROW['userid']);
    if ($row_USER['gender'] == "Female") {

        $image = "images/icons/user_female.jpg";
    }

    if (file_exists($row_USER['profile_image'])) {
        $image =  $image_class->get_profile_thumb($row_USER['profile_image']);
    }
    $special_char = false;
    if($row_USER['verified'] == '1'){
        $special_char = true;
    }
    ?>

    <img id="user-photo-comment" src="<?php echo $image ?>">
</div>
<div id="post_comment">



    <div>


        <div><span><a id="comment-user-name" href="profilepage.php?id=<?php echo $COMMENT['userid'] ?>"><?php echo htmlspecialchars($row_USER['first_name']) . " " . htmlspecialchars($row_USER['last_name']) ?> 
        <?php
                if($special_char){
                    echo "<span><img style='width:12px' id='comment_verify' src='images/icons/verify.svg' ></span>";
                }
                ?>
         · <span id='comment-date'><?php 
         $object_time = new Time();
         $comment_time = $object_time->get_time($COMMENT['date']);
         echo $comment_time;/* Time::get_time( $COMMENT['date']) */?></span></a></span>
            <span style="font-size:13px;color:#000f7d;font-weight:normal">
                <?php
                if ($COMMENT['is_profile_image']) {

                    $pronoun = 'his';
                    if ($row_USER['gender'] == "Female") {
                        $pronoun = "her";
                    }
                    echo "<span id='user-updates'> - updated $pronoun profile image</span>";
                } else
            if ($COMMENT['is_cover_image']) {
                    $pronoun = 'his';
                    if ($row_USER['gender'] == "Female") {
                        $pronoun = "her";
                    }
                    echo "<span id='user-updates'> - updated $pronoun cover image</span>";
                }

                ?>
            </span>
        </div>

        <div><span id="comment_post"><?php echo htmlspecialchars($COMMENT['post']) ?></span></div>
        <br>
        <div>
            <?php
            if (file_exists($COMMENT['image'])) {
                $post_image = $image_class->get_post_thumb($COMMENT['image']);
                echo "<img  id='post-official-image' src='$post_image' style='width:80%;' >";
            }
            ?>
        </div>
        <!-- <br style="clear:both"> -->
        <?php

        $likes = "";

        $likes = ($COMMENT['likes'] > 0) ?  $COMMENT['likes'] : "";



        ?>
        <?php

        $last_location = "<script>window.location.href</script>";
        // echo $last_location;
        $_SESSION['return_to'] = $last_location;
        ?>
        <div id='bottom-post'>
        <span id="details_span"><a id="like-tag" href="like.php?type=post&id=<?php echo $COMMENT['postid'] ?>"> <span>
            <img id="liker_svg" src="images/icons/like-light.svg" style="width:15px;margin-right:1px" alt="">
        </span><?php echo $likes ?> </a>  <a id="reply_tag" style="width:105px;" href="apello.php?id=<?php echo $COMMENT['postid'] ?> "></span>
        <img id='liker_svg' src='images/icons/reply-light.svg' alt=''>
    </a></span>
        
        <?php

        if ($COMMENT['has_image']) {

            echo "<a id='date-post' href='image_view.php?id=$COMMENT[postid]' style='color:#999;text-decoration:none'>";
            // echo " . View Full Image";
            echo "<img id='liker_svg' src='images/icons/expand-light.svg' alt=''>";
            echo "</a>";
        }

        ?>

        <span style="margin: 0px;color:#999;">
            <?php

            $post = new Post();
            if ($post->i_own_post($COMMENT['postid'], $_SESSION['apello_userid'])) {
                // echo " | ";
                echo "<a id='date-post' href='edit.php?id=$COMMENT[postid]' style='text-decoration:none;color:#bbb'>
                <img id='liker_svg' src='images/icons/edit-light.svg' alt=''>
                </a>
                    
                    <a id='date-post' href='delete.php?id=$COMMENT[postid]' style='text-decoration:none;color:#bbb'>
                    <img id='liker_svg' src='images/icons/delete-light.svg' alt=''>
                    </a>
                    ";
            }

            $sql = "SELECT likes FROM likes WHERE type = 'post' && contentid = '$COMMENT[postid]' LIMIT 1";
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

                    if ($COMMENT['likes'] > 0) {
                        echo "<br>";
                        echo "<div><a style='text-decoration:none; color:#999;font-size:10px;' href='likes.php?type=post&id=$COMMENT[postid]' ></div>";

                        if ($COMMENT['likes'] == 1) {

                            if ($i_liked) {
                                echo "You liked this comment";
                            } else {
                                if (!$i_liked) {
                                    echo "1 person liked this comment";
                                }
                            }
                        } else {

                            if ($COMMENT['likes'] > 1) {
                                if ($i_liked) {


                                    if ($i_liked && $COMMENT['likes'] == 2) {
                                        echo "You and 1 person liked this";
                                    } else {
                                        $_user_likes = $COMMENT['likes'] - 1;
                                        echo "You and " . $_user_likes . " others liked this";
                                    }
                                } else {

                                    echo $COMMENT['likes']  . " people liked this comment";
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
</div>