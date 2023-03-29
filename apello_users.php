<div id="friends" style="margin-bottom:10px;">
    <?php 
        $image_class = new Image();
        $image = "images/icons/user_male.jpg";
        if($FRIEND_ROW['gender'] == "Female"){
        
        $image = "images/icons/user_female.jpg";

        }
        if(file_exists($FRIEND_ROW['profile_image'])){
            $image =  $image_class->get_profile_thumb($FRIEND_ROW['profile_image']);
        }
        $special_char = false;
        // print_r($FRIEND_ROW);
        if($FRIEND_ROW['verified'] == '1'){
            $special_char = true;
        }
    ?>

    <a href="profilepage.php?id=<?php echo $FRIEND_ROW['userid']; ?>" style="text-decoration:none;color:#405dbd;">
    <img id="img_apello_friends" src="<?php echo $image ?>">
    <br>
    <span id="apello_text_friend" ><?php echo $FRIEND_ROW['first_name'] . " " . $FRIEND_ROW['last_name'] ?>
    <?php
        if($special_char){
            echo "<span><img id='verify' style='width:12px;' src='images/icons/verify.svg'></span>";
        }
    ?>
    <span>
    </a>
</div>