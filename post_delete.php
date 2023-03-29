<div id="post_div"style="width:100%;height:5px;padding:0;margin:0;background-color:#d0d8e4"> </div>
<div id="post" style="word-break:break-all"> 

    <div>
        <?php 
        $image = "images/icons/user_male.jpg";

        if($row_USER['gender'] == "Female"){
            
            $image = "images/icons/user_female.jpg";
        }
        
        if(file_exists($row_USER['profile_image'])){
            $image =  $image_class->get_profile_thumb($row_USER['profile_image']);
        }
        ?>
       
        <img id="user-photo" src="<?php echo $image ?>">
    </div>
    
    <div>
        <div id="user-name"><span ><a href="profilepage.php?id=<?php echo $row['userid'] ?>" style="text-decora tion:none;font-weight: bold;color: #405d9b;"><?php echo htmlspecialchars($row_USER['first_name']) . " " . htmlspecialchars($row_USER['last_name']) ?></a></span>
        
    </div>

        <div><?php echo htmlspecialchars($row['post']) ?></div>
        <br>
        <div>
            <?php 
                if(file_exists($row['image'])){
                    $post_image = $image_class->get_post_thumb($row['image']);
                    echo "<img  id='post-official-image' src='$post_image' style='width:80%;' >";
                } 
            ?>
        </div>
        <br><br style="clear:both">
        
       
        
    </div>
</div>