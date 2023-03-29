<?php

class Status
{

    private $error = "";

    public function upload_status($userid, $data, $files)
    {

        // print_r($data);
        if (empty($files['file']['name'])) {
            $has_image = 0;
            $type = "text";
        }
        if (!empty($data['post']) || !empty($files['file']['name']) || isset($data['is_profile_image']) || isset($data['is_cover_image'])) {
            $my_image = "";
            $has_image = 0;
            $is_cover_image = 0;
            $is_profile_image = 0;

            if (isset($data['is_profile_image']) || isset($data['is_cover_image'])) {

                $my_image = $files;
                $has_image = 1;
                $type = "picture";

            } else {

                if (!empty($files['file']['name'])) {

                    $folder = "uploads/" . $userid . "/";

                    //create folder
                    if (!file_exists($folder)) {
                        mkdir($folder, 0755, true);
                        file_put_contents($folder . "index.php", "");
                    }

                    $image_class = new Image();

                    $my_image = $folder . $image_class->generate_filename(15) . "_status.jpg";
                    
                    if(move_uploaded_file($_FILES['file']['tmp_name'], $my_image)){
                        // echo "File uploaded";
                    }else{
                        copy($_FILES['file']['tmp_name'], $my_image);
                        // echo "File not uploaded";
                    }
                    
                    // die;
                    $image_class->resize_image($my_image, $my_image, 1500, 1500);

                    $has_image = 1;
                    $type = "picture";
                }
            }

            $post = "";
            $DB = new Database();
            if (isset($data['post'])) {


                $post = addslashes($data['post']);
            }

            $postid = $this->create_status_id();
            // $parent = 0;
            if (empty($files['file']['name'])) {
                $has_image == 0;
            }

            // if($type == "post")
            if(isset($userid)){
                $json_array=[];
                $json_array['userid'] = $userid;
                $json_array['postid'] = $postid;
                // $json_array['owner_id'] = $id;
                $notis_ = new Notifications();
                $type = "status";
                $json_array_notis = json_encode($json_array);
                $notis_->addNotis($userid, $type, $json_array_notis);
            } 

            $query = "INSERT INTO status (userid, postid, caption, image, has_image, type) VALUES ('$userid', '$postid', '$post', '$my_image', '$has_image', '$type')";
            // echo $query;
            $DB->save($query);
        } else {

            $this->error .=  "Select a photo or enter text to post!<br>";
        }

        return $this->error;
    }

    private function create_status_id()
    {

        $length = rand(4, 10);
        $number = "";
        for ($i = 0; $i < $length; $i++) {
            $new_rand = rand(0, 9);
            $number = $number . $new_rand;
        }
        return $number;
    }

    function get_statuses()
    {

        $query = "SELECT * FROM status /* WHERE DATEDIFF(date,CURRENT_TIMESTAMP()) = 0  */ ORDER BY id DESC LIMIT 50";
        // echo $query;
        // die;
        // $query = "SELECT * FROM posts  ORDER BY id DESC LIMIT 20";

        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    function get_user_with_status() //culprit (unable to remove posts older than 24 hours due to layout below):
    {

        $query = "SELECT userid FROM status GROUP BY id ORDER BY date DESC LIMIT 50";
        // echo $query;
        // die;
        // $query = "SELECT * FROM posts  ORDER BY id DESC LIMIT 20";

        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    function stat_number($id)
    {


        $sql = "SELECT '$id', COUNT(userid) AS count FROM status WHERE userid = '$id' GROUP BY '$id'";
        // echo $sql;
        // die;
        $DB = new Database();
        $result = $DB->read($sql);
        // print_r($result);
        // die;
        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    function get_latest_status($id)
    {
        $sql = "SELECT * FROM status WHERE userid = '$id' ORDER BY date ASC LIMIT 30";
        // echo $sql;
        // die;
        $DB = new Database();
        $result = $DB->read($sql);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    function get_user_statuses($id)
    {
        $sql = "SELECT * FROM status WHERE userid = '$id' ORDER BY date DESC LIMIT 30";
        // echo "<script>alert('$sql')</script>";
        // echo $sql;
        // echo $sql;
        $DB = new Database();
        $result[0] = $DB->read($sql);

        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    function get_post_id($caption, $userid, $date)
    {

        $DB = new Database();
        $sql = "SELECT postid FROM status WHERE userid = '$userid' && caption = '$caption' &&  date ='$date' LIMIT 1";
        $result = $DB->read($sql);

        if (is_array($result)) {
            return $result[0]['postid'];
        } else {
            return false;
        }
    }

    public function delete_user_status($postid)
    {
        $DB = new Database();

        if (!is_numeric($postid)) {
            return false;
        }

        $query = "DELETE FROM status WHERE postid = '$postid' LIMIT 1";

        $DB->save($query);
    }

    public function get_postid_user($postid)
    {

        $sql = "SELECT userid FROM status WHERE postid = '$postid' LIMIT 1";

        $DB = new Database();
        $result = $DB->read($sql);
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    public function get_views($postid)
    {

        $DB = new Database();
        // echo $postid;
        $counter = 0;
        $sql = "SELECT get FROM status WHERE postid = '$postid' && userid = '$_SESSION[apello_userid]' LIMIT 1";
        $result = $DB->read($sql);

        return $result[0];
     
    }


    public function view_status($postid, $users_id)
    {
        date_default_timezone_set('Africa/Johannesburg');
        $DB = new Database();

        //save likes details
        
        $sql = "SELECT * FROM status WHERE postid = '$postid' LIMIT 1";
        $result = $DB->read($sql);
        // print_r($result);

        $owner_id = $result[0]['userid'];
        if (is_array($result)) {    
            $views = json_decode($result[0]['views'], true);
            if(!empty($views)){
                //if array for views includes any entries
                $user_ids = array_column($views, "userid");
            }else{
                //else leave empty to prevent null error
                $user_ids = [];
            }
            
            //problem with $users_id and $owner_id
            if (!in_array($users_id, $user_ids)) {
                $arr["userid"] = $users_id;
                $arr["date"] = date('Y-m-d H:i:s');
                // echo $arr['date'];
                // die;
               
                //add to array to append to json
                $views[] = $arr;

                $views_string = json_encode($views);
                $sql = "UPDATE status SET views = '$views_string' WHERE postid ='$postid' LIMIT 1";
                $DB->save($sql);

                $sql = "UPDATE status SET get = get + 1 WHERE postid = '$postid' LIMIT 1";
                $DB->save($sql);
            }else{
                // no error, in array, do nothing
            }
        } else {
            $key = array_search($users_id, $result['userid']);
            unset($views[$key]);
            $arr["userid"] = $users_id;
            $arr["date"] = date('Y-m-d H:i:s');

            $arr2[] =  $arr;
            $views_2 = json_encode($arr2);
            $sql = "UPDATE status SET views = '$views_2' WHERE postid ='$postid' LIMIT 1";
            $DB->save($sql);

            //Increment the right table
            $sql = "UPDATE status SET get = get + 1 WHERE postid = '$postid' LIMIT 1";

            $DB->save($sql);
        }
    }
}
