<div>
    <div>
        <img src="" alt="">

    </div>
    <form method="post" enctype="multipart/form-data">
        <div id="stat_heading">New Status:
            <div id="text-stuff"><textarea name="post" placeholder="Enter caption" style="margin-bottom:5px"></textarea></div>
            <div><input id="upload-button" name="file" style="display:none" type="file" accept="image/jpeg" onchange="document.getElementById('upload-text').innerHTML = 'Photo selected...'"></div>
            <label for="upload-button" id="upload-text">SELECT PHOTO</label>
            <button type="submit" id="post-button">Post Status</button><br>
        </div>
        <div id="pic_div" style="text-align:center"></div>
    </form>
    <?php
if(!empty($user_posts)){

    $status_count = new Status();
    $counter = $status_count->stat_number($_SESSION['apello_userid']);
    
    if(!empty($counter)){
        $count = $counter['count'];
    }else{
        $count = 0;    
    }
    
}else{
    $count = 0;
}
    ?>
    <div id="current_stat_heading" style="height:10px">Current Statuses: (<?php echo $count ?>)</div>
    <?php
    $get_my_stat = new Status();
    $stat_row = $get_my_stat->get_user_statuses($_SESSION['apello_userid']);
    //$stat_row = array_unique($user_posts, SORT_REGULAR);
    if ($stat_row) {
        foreach ($stat_row as $my_stat) {
            $_statuses = $my_stat;
            include('my_stat.php');
        }
    } {
    }
    ?>
</div>