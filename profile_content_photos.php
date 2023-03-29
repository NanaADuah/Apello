<div id="photo-content" style="width: 100%; padding-bottom:10px;display: -moz-grid-group;padding-top:10px;margin-top:5px;min-height: 400px;text-align:center">

<?php

        $DB = new Database();
        $sql = "SELECT image, postid FROM posts WHERE has_image = 1 && userid =$user_data[userid]  ORDER BY id DESC LIMIT 30";

        $images = $DB->read($sql);

        $image_class = new Image();
        if(is_array($images)){

            
            foreach($images as $image_row){

                if(file_exists($image_row["image"])){
                    $post_image = $image_class->get_post_thumb($image_row['image']);
                    //  print_r($images);
                    // print_r($images[0]['postid']);
                    // $image_id = ;
                    // print_r($images['postid']);
                    // die;
                    $image_id = $images[0]['postid'];

            echo "<a href='$post_image'><span><img id='post_image_section' style='margin:10px;width:150px;' src=". $post_image . "></span><a>";
                }
        }
        }else{
            echo "No images were found.";
        }

    ?>
    
</div>