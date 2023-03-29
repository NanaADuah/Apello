<?php

class User{

    public function get_data($id){

        $query = "SELECT * FROM users WHERE userid = '$id' LIMIT 1";

        $DB = new Database;
        $result = $DB->read($query);

        if($result){
            $row =  $result[0];
            return $row; 
        }else{
            return false;
        }
    }

    public function get_user($id){
        
        $query = "SELECT * FROM users WHERE userid = '$id' LIMIT 1";
        $DB = new Database();
        $result = $DB->read($query); 

        if($result) {
            return $result[0];
        }else{
            return false;
        }
    }

    public function get_comment_user($id){
        
        $query = "SELECT * FROM users WHERE userid = '$id' LIMIT 1";

        $DB = new Database();
        $result = $DB->read($query); 

        if($result) {
            return $result;
        }else{
            return false;
        }
    }

    public function get_friends($id){

        $query = "SELECT * FROM users WHERE userid != '$id'";

        $DB = new Database();
        $result = $DB->read($query); 

        if($result) {
            return $result;
        }else{
            return false;
        }
    }

    public function get_following($id, $type){

        $DB = new Database();

        if (is_numeric($id)) {

            //get following details
            $sql = "SELECT following FROM likes WHERE type = '$type' && contentid = '$id' LIMIT 1";
            $result = $DB->read($sql);

            if (is_array($result)) {

                $following = json_decode($result[0]['following'], true);
                return $following;
            }
        } else {
            return false;
        }
    }

    public function get_followers($id, $type){

        $DB = new Database();

        if (is_numeric($id)) {

            //get following details
            $sql = "SELECT likes FROM likes WHERE type = '$type' && contentid = '$id' LIMIT 1";
            $result = $DB->read($sql);

            if (is_array($result)) {

                $followers = json_decode($result[0]['likes'], true);
                return $followers;
            }
        } else {
            return false;
        }
    }


    public function follow_user($id, $type, $apello_userid)
    {

            $DB = new Database();

            //save likes details
            $sql = "SELECT following FROM likes WHERE type = '$type' && contentid = '$apello_userid' LIMIT 1";
            $result = $DB->read($sql);


            if (is_array($result)) {

                $likes = json_decode($result[0]['following'], true);

                $user_ids = array_column($likes, "userid");

                if (!in_array($id, $user_ids)) {

                    $arr["userid"] = $id;
                    $arr["date"] = date('Y-m-d H:i:s');

                    $likes[] = $arr;

                    $likes_string = json_encode($likes);
                    $json_array =[];
                    $json_array['userid'] = $apello_userid;
                    $json_array['follower'] = $id;
                    $notis_ = new Notifications();
                    $type = "followed";
                    $json_array_notis = json_encode($json_array);
                    $notis_->addNotis($apello_userid, $type, $json_array_notis);
                    $sql = "UPDATE likes SET following = '$likes_string' WHERE type ='$type' && contentid='$apello_userid' LIMIT 1";
                    $DB->save($sql);
                } else {
                    $key = array_search($id, $user_ids);
                    unset($likes[$key]);
                    $likes_string = json_encode($likes);
                    $sql = "UPDATE likes SET following = '$likes_string' WHERE type ='$type' && contentid='$apello_userid' LIMIT 1";
                    $DB->save($sql);

                }
            } else {

                $arr["userid"] = $id;
                $arr["date"] = date('Y-m-d H:i:s');

                $arr2[] =  $arr;
                $following = json_encode($arr2);
                $sql = "INSERT INTO likes (type, contentid, following) VALUES ('$type', '$apello_userid', '$following')";
                $DB->save($sql);

            }
    }
}

