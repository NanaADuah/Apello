<div id="friends">
    <?php 
        $image_class = new Image();
        if(isset($FRIEND_ROW)){
            // print_r($FRIEND_ROW['userid']);
        $image = "images/icons/user_male.jpg";

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
    

    echo "<a href='profilepage.php?id=$FRIEND_ROW[userid]' style='text-decoration:none;color:#405dbd;'>
    <img id='friend-img' src='$image'>
    <br>
    <span id='apello_text_friend'>$FRIEND_ROW[first_name] $FRIEND_ROW[last_name]  ";
    
    if($special_char){
                echo "<img style='float:unset;margin:0px;width:11px;' src='images/icons/verify.svg'>";
            }
    echo  "</span></a>";
    
    }   
    ?>

</div>