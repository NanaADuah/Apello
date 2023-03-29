<div id="photo-content" style="padding-bottom:10px;display: grid;width: 100%; padding-top:10px;margin-top:5px;text-align:center">

<?php

        $image_class = new Image();
        $post_class = new Post();
        $user_class = new User();
        $followers = $post_class->get_likes($user_data['userid'], "user");
        // print_r($followers);
        // die;

        if(is_array($followers) && $counter > 0){
        $counter = count($followers);
        // echo $counter;
        // die;
        if(!empty($followers)){
            if($counter > 1){
            foreach($followers as $follower){
                // $counter++;
                // echo $follower;
                print_r($follower);

                    $FRIEND_ROW = $user_class->get_user($follower['userid']);
                    include("user.php");
                }

        }
    }else{
        if($counter == 1){
            // print_r($followers);

            $FRIEND_ROW = $user_class->get_user($followers[0]['userid']);
            include("user.php");

        }
    }
    }else{
            echo "No followers were found.";
        }

    ?>
    
</div>