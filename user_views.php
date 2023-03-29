<div id="friends">
    <?php 
        $image_class = new Image();
        $image = "images/icons/user_male.jpg";
        $view_date = Time::get_time($date);
        if($FRIEND_ROW['gender'] == "Female"){
        
        $image = "images/icons/user_female.jpg";

        }
        if(file_exists($FRIEND_ROW['profile_image'])){
            $image =  $image_class->get_profile_thumb($FRIEND_ROW['profile_image']);
        }
        $special_char = false;
        if($FRIEND_ROW['verified'] == '1'){
            $special_char = true;
        }
    ?>

    <a href="profilepage.php?id=<?php echo $FRIEND_ROW['userid']; ?>"style="text-decoration:none;color:#405dbd;">
    <img id="friend-img" src="<?php echo $image ?>">
    <br>
    <span id="apello_text_friend"><?php echo $FRIEND_ROW['first_name'] . " " . $FRIEND_ROW['last_name'] ?>
    <?php
        if($special_char){
            echo "<span><img id='verify' style='width:12px;margin:0px;float:none;' src='images/icons/verify.svg'></span>";
        }
    ?>
    <span>
    <br>
    <span style="font-weight:lighter;font-style:italic;color:#7a7a7a"><?php echo $view_date?></span>
    </a>
</div>