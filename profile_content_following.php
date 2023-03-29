<div id="photo-content" style="width: 100%;padding-bottom:10px;display: grid; padding-top:10px;margin-top:5px;min-height: 400px;text-align:center">

<?php

        $image_class = new Image();
        $post_class = new Post();
        $user_class = new User();
        $following = $user_class->get_following($user_data['userid'], "user");

        if(is_array($following)){
            foreach($following as $follower){

                $FRIEND_ROW = $user_class->get_user($follower['userid']);
                include("user.php");

        }
        }else{
            echo "This user isn't following anyone.";
        }

    ?>
    
</div>