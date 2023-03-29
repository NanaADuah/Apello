<?php

class Post
{

    private $error = "";
    public function create_post($userid, $data, $files)
    {
        // date_default_timezone_set('Africa/Johannesburg');
        $type = "postupdate";
        if (!empty($data['post']) || !empty($files['file']['name']) || isset($data['is_profile_image']) || isset($data['is_cover_image'])) {
            $my_image = "";
            $has_image = 0;
            $is_cover_image = 0;
            $is_profile_image = 0;

            if (isset($data['is_profile_image']) || isset($data['is_cover_image'])) {

                $my_image = $files;
                $has_image = 1;

                if (isset($data['is_cover_image'])) {
                    $is_cover_image = 1;
                    $post_update = "cover";
                }

                if (isset($data['is_profile_image'])) {
                    $is_profile_image = 1;
                    $post_update = "profile";
                    $type = "profilechange";
                }
            } else {

                if (!empty($files['file']['name'])) {

                    $folder = "uploads/" . $userid . "/";

                    //create folder
                    if (!file_exists($folder)) {
                        mkdir($folder, 0755, true);
                        file_put_contents($folder . "index.php", "");
                    }

                    $image_class = new Image();

                    $my_image = $folder . $image_class->generate_filename(15) . ".jpg";
                    move_uploaded_file($_FILES['file']['tmp_name'], $my_image);
                    $image_class->resize_image($my_image, $my_image, 1500, 1500);

                    $has_image = 1;
                }
            }

            $post = "";
            $DB = new Database();
            if (isset($data['post'])) {
                $post = addslashes($data['post']);
            }

            $postid = $this->create_postid();
            $parent = 0;

            if(isset($data['parent']) && is_numeric($data['parent'])){

                $parent = $data['parent'];
                $type = "comment";

 
                $sql = "UPDATE posts SET comments = comments + 1 WHERE postid = '$parent' LIMIT 1";
                $DB->save($sql);
            }

            if($type == 'postupdate'){
                $notis_array['userid'] = $_SESSION['apello_userid']; 
                $notis_array['postid'] = $postid;
                $json_array_notis = json_encode($notis_array);
                $new_notis = new Notifications;
                $notis_result = $new_notis->addNotis($_SESSION['apello_userid'], $type, $json_array_notis);
                // print_r($json_array_notis);
            }else
            if($type == 'profilechange'){
                $notis_array['userid'] = $_SESSION['apello_userid'];
                $notis_array['image'] = $my_image;
                $notis_array['update_type'] = $post_update;
                $json_array_notis = json_encode($notis_array);  
                $new_notis = new Notifications;
                $new_notis->addNotis($_SESSION['apello_userid'], $type, $json_array_notis);  
            }else
            if($type == 'comment'){
                $notis_array['userid'] = $_SESSION['apello_userid']; 
                $notis_array['postid'] = $postid;
                $notis_array['postid_owner_id'] = $data['parent'];
                $json_array_notis = json_encode($notis_array);
                $new_notis = new Notifications;
                $new_notis->addNotis($_SESSION['apello_userid'], $type, $json_array_notis);  
            }
            // die;
            $query = "INSERT INTO posts (userid, postid, post, image, has_image, is_cover_image, is_profile_image, parent) VALUES ('$userid', '$postid', '$post', '$my_image', '$has_image','$is_cover_image', '$is_profile_image', '$parent')";

            
            $DB->save($query);
        } else {

            $this->error .=  "Post area is empty!<br>";
        }

        return $this->error;
    }

    private function create_postid()
    {

        $length = rand(4, 10);
        $number = "";
        for ($i = 0; $i < $length; $i++) {
            $new_rand = rand(0, 9);
            $number = $number . $new_rand;
        }
        return $number;
    }

    public function get_post($id)
    {

        $query = "SELECT * FROM posts WHERE userid = '$id'  ORDER BY id DESC LIMIT 20";
        // $query = "SELECT * FROM posts  ORDER BY id DESC LIMIT 20";

        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function get_main_page_post($id)
    {
        $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page_number = ($page_number <= 0) ? 1: $page_number;
        $limit = 10;
        $offset = ($page_number - 1) * $limit;
        
        $query = "SELECT * FROM posts WHERE userid = '$id' && parent = 0 ORDER BY id DESC LIMIT $limit OFFSET $offset";
        // $query = "SELECT * FROM posts  ORDER BY id DESC LIMIT 20";

        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function get_likes($id, $type){

        $DB = new Database();

        if (is_numeric($id)) {

            //get likes details
            $sql = "SELECT likes FROM likes WHERE type = '$type' && contentid = '$id' LIMIT 1";
            $result = $DB->read($sql);

            if (is_array($result)) {

                $likes = json_decode($result[0]['likes'], true);
                return $likes;
                // print_r($likes);
                // die;
            }
        } else {
            return false;
        }
    }
    public function delete_post($postid)
    {
        $DB = new Database();

        if (!is_numeric($postid)) {
            return false;
        }

        $sql = "SELECT parent FROM posts WHERE postid = '$postid' LIMIT 1"; 
        $result = $DB->read($sql);
       
        if(is_array($result)){

            if($result[0]['parent'] > 0){
            //if(isset($data['parent']) && is_numeric($data['parent']))
            {

                $parent = $result[0]['parent'];
                $sql = "UPDATE posts SET comments = comments - 1 WHERE postid = '$parent' LIMIT 1";
               // echo $sql;
                //die;
                $DB->save($sql);
            }
        }
        
        $query = "DELETE FROM posts WHERE postid = '$postid' LIMIT 1";

        $DB->save($query);}
    }

    public function get_one_post($postid)
    {

        if (!is_numeric($postid)) {
            return false;
        }
        $query = "SELECT * FROM posts WHERE postid = '$postid' LIMIT 1";

        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function i_own_post($postid, $apello_userid = 0)
    {

        //if(!is_numeric($apello_userid)){
        //   return false;
        //}

        $query = "SELECT * FROM posts WHERE postid = '$postid' LIMIT 1";



        $DB = new Database();
        $result = $DB->read($query);


        if (is_array($result)) {

            if ($result[0]['userid'] == $apello_userid) {
                return true;
            }
        }
        return false;
    }

    public function change_rating($userid,$rating){
        $DB = new Database();
        $sql = "UPDATE users SET rating = '$rating' WHERE userid = '$userid'";
        // echo $sql;
        // echo ("<script> console.log('Random counter: '" . '$sql' . "') </script>");
        
        $DB->save($sql);
    }

    public function like_post($id, $type, $apello_userid)
    {

            $DB = new Database();

            //save likes details
            $sql = "SELECT likes FROM likes WHERE type = '$type' && contentid = '$id' LIMIT 1";
            $result = $DB->read($sql);
// 
            // print_r($result);
            // die;
            // echo "<br>";
            
            if (is_array($result) && (!empty($result))) {
                // echo "yes";
                $likes = json_decode($result[0]['likes'], true);
                // echo "<br>";
                // print_r($likes) ;
                $likes = $likes;
                // die;
                // echo " likes array";
                // echo "<br>";
                // echo "<br>" ;
                $user_ids = array_column($likes, "userid");
                // $second_userid ;
                // print_r($user_ids);
                // echo "<br>";
                // echo "Values of user ids above"; 
                // echo "<br>";
                // print_r($user_ids); /////////////////FIXXXXXXXX <------
                // echo "<br>";

                if (!in_array($apello_userid, $likes)) {
                    // echo "add";
                    // echo "<br>";
                    $arr["userid"] = $apello_userid;
                    $arr["date"] = date('Y-m-d H:i:s');
                    
                    $likes = $arr;
                    
                    // echo "<br>";
                    $likes_string = json_encode($likes);

                    if($type == "post"){
                        $json_array=[];
                        $json_array['userid'] = $apello_userid;
                        $json_array['postid'] = $id;
                        // $json_array['owner_id'] = $id;
                        $notis_ = new Notifications();
                        $json_array_type = "likedpost";
                        $json_array_notis = json_encode($json_array);
                        $notis_->addNotis($apello_userid, $json_array_type, $json_array_notis);
                    }
                    $sql = "UPDATE likes SET likes = '$likes_string' WHERE type ='$type' && contentid='$id' LIMIT 1";
                    // die;
                    $DB->save($sql);
                    
                    //Increment the right table
                    $sql = "UPDATE {$type}s SET likes = likes + 1 WHERE {$type}id = '$id' LIMIT 1";
                    // echo $sql . " adding -> $apello_userid";
                    // die;
                    $DB->save($sql);
                } else {
                    // echo "remove";
                    // print_r($user_ids);

                    // die;
                    // $key = array_search($apello_userid, $user_ids); backup, initially didn't work .. replaced by likes array
                    $key = array_search($apello_userid, $likes);
                    unset($likes[$key]);
                    $likes_string = json_encode($likes);
                    $sql = "UPDATE likes SET likes = '$likes_string' WHERE type ='$type' && contentid='$id' LIMIT 1";
                    $DB->save($sql);


                    //Increment the right table
                    $sql = "UPDATE {$type}s SET likes = likes - 1 WHERE {$type}id = '$id' LIMIT 1";
                    // echo $sql . " aaaapellllooo";

                    $DB->save($sql);

                }
            } else {

                $arr["userid"] = $apello_userid;
                $arr["date"] = date('Y-m-d H:i:s');

                $arr2[] =  $arr;
                $likes = json_encode($arr2);
                $sql = "INSERT INTO likes (type, contentid, likes) VALUES ('$type', '$id', '$likes')";
                $DB->save($sql);


                //Increment the right table
                $sql = "UPDATE {$type}s SET likes = likes + 1 WHERE {$type}id = '$id' LIMIT 1";

                $DB->save($sql);
            }
            // die;
    }

    public function edit_post($data, $files){

        if (!empty($data['post']) || !empty($files['file']['name'])) {
            $my_image = "";
            $has_image = 0;

            if (!empty($files['file']['name'])) {

                $folder = "uploads/" . $userid . "/";

                //create folder
                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true);
                    file_put_contents($folder . "index.php", "");
                }

                $image_class = new Image();

                $my_image = $folder . $image_class->generate_filename(15) . ".jpg";
                move_uploaded_file($_FILES['file']['tmp_name'], $my_image);
                $image_class->resize_image($my_image, $my_image, 1500, 1500);

                $has_image = 1;
            }

            $post = "";

            if (isset($data['post'])) {


                $post = addslashes($data['post']);
            }
            
            $postid =  addslashes($data['postid']);

            if ($has_image) {
                $query = "UPDATE posts SET post = '$post', image = '$my_image' WHERE postid = '$postid' LIMIT 1";
            } else {
                $query = "UPDATE posts SET post = '$post' WHERE postid = '$postid' LIMIT 1";
            }

            $DB = new Database();
            $DB->save($query);
        } else {

            $this->error .=  "Post area is empty!<br>";
        }

        return $this->error;
    }

    public function get_all_post($id)
    {

        $query = "SELECT * FROM posts WHERE parent = 0 ORDER BY id DESC LIMIT 20";
        // $query = "SELECT * FROM posts  ORDER BY id DESC LIMIT 20";

        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function get_comments($id)
    {
        $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page_number = ($page_number <= 0) ? 1: $page_number;
        $limit = 5;
        $offset = ($page_number - 1) * $limit;
        

        $query = "SELECT * FROM posts WHERE parent = '$id'  ORDER BY id ASC LIMIT $limit OFFSET $offset";
        // echo $query;

        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function get_comment_count($id)
    {

        $query = "SELECT COUNT(*) AS count FROM posts WHERE parent = '$id'";

        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

}
