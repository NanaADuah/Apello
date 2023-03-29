<div id="content-holder">

    <!-- friends -->
    <div id="friend-content">
       <div id="friend-bar">
        
        <a id="stat-link" href="status.php?section=default&id=<?php echo $_SESSION['apello_userid']?>&count=1&current=0" ><div id="stories_panel">Stories</div></a>
        <a style='text-decoration:none' href="profilepage.php?section=followers&id=<?php echo $user_data['userid'] ?>"><div id='follow_panel'>Followers</div></a>
        <a style='text-decoration:none' href="profilepage.php?section=following&id=<?php echo $user_data['userid'] ?>"><div id='follow_panel'>Following</div></a>
        <a style='text-decoration:none' href="help.php"><div id='follow_panel'>Help</div></a>
        <div style="border-top:3px solid rgba(0,0,0,0.23);width:100%; height:5px"></div>
            <span id="apello_users">Apello Users</span><br>

            <?php

            if ($friends) {
                foreach ($friends as $FRIEND_ROW) {
                    include("user.php");
                }
            }
            ?>
        </div>
    </div>

    <!-- post area -->
    <div id="post-content">
        <div id="post-one">
            <form method="post" enctype="multipart/form-data" style="margin-bottom:5px">
                <textarea style="margin-bottom:5px;" name="post" placeholder="What's on your mind?"></textarea>
                
                <input type="file" name="file" id="upload-button" accept="image/jpeg" onchange="document.getElementById('upload-text').innerHTML = 'File selected...'">
                <span class="post_uploads"><label for="upload-button" id="upload-text">
                        <?php
                        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {
                            echo "FILE READ TO POST.";
                        } else {
                            echo "SELECT FILE";
                        }
                        ?>
                    </label>
                </span>
                <input type="submit" id="post-button" value="Post">

                <br>
            </form>
        </div>
        <!-- <a href="https://www.SmarterASP.NET/index?r=nana321"><img src="https://www.SmarterASP.NET/affiliate/728X90.gif" style='border:none'></a> -->
   
        <!-- posts-->
        <div id="post-bar">

            <?php

            if ($posts) {
                foreach ($posts as $ROW) {
                    $user = new User();
                    $row_USER = $user->get_user($ROW['userid']);
                    include("post.php");
                }
            }

             //get current url
             $pg = pagination_link();

            ?>
           
        </div>
                    <div style='display:flex'>
                    <a id='ref-page' href="<?= $pg['prev_page'] ?>">
                            <input id="page-button" style="flex:1;float:right;width:50%;margin-right:5px" value="Previous Page">
                        </a>
                        <a id='ref-page' href="<?= $pg['next_page'] ?>">
                            <input id="page-button" style="flex:1;float:left;width:50%;margin-left:5px" value="Next Page">
                        </a>
                        <br>
                        <br>
                        
                    </div>    
    </div>
    
</div>