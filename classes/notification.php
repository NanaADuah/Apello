<?php

class Notifications
{

    public function addNotis($userid, $type, $notis)
    {
        $sql = "INSERT INTO notifications (userid,type,notis) VALUES ('$userid', '$type','$notis')";

        $DB = new Database();
        $DB->save($sql);
    }

    public function get_notifications()
    {
        //get all notifications, not filtered
        $DB = new Database();
        $sql = "SELECT * FROM notifications ORDER BY date  DESC LIMIT 20";

        $result = $DB->read($sql);

        if (!empty($result)) {
            return $result;
        } else {
            return "";
        }
    }

    function filter_notis()
    {
    }

    public function get_followers($id, $type){

        $DB = new Database();

        if (is_numeric($id)) {

            //get following details
            $sql = "SELECT likes FROM likes WHERE type = '$type' && contentid = '$id' LIMIT 1";
            $result = $DB->read($sql);
            // print_r($result);
            // echo $sql;

            //return followers filtering the array 
            if (is_array($result) && !empty($result)) {
                // echo "not em
                // echo "nigga";   
                // pty";
                $followers = json_decode($result[0]['likes'], true);
                // print_r($followers);
                // echo "<br>";
                if(!empty($followers)){
                    foreach($followers as $information){
                        if(isset($information['userid'])){
                            $user_id = $information['userid'];
                            $arr[] =  $user_id;
                            return $arr;
                        }
                    }
                }
                // print_r($arr);
            }
            
        } else {
            return false;
        }
    }

    public function unread_notis($followers, $my_id)
    {
        $sql = "SELECT * FROM notifications ORDER BY date DESC LIMIT 20";
        $DB = new Database();
        $check_it = false;
        $result = $DB->read($sql);
        // print_r($followers);
        if (!empty($result) && !empty($followers)){
            // echo "yes";
            $check_it = true;
            foreach($followers as $follow){
                // echo "<pre>";
                // print_r($follow);
                $user_ = $follow;
                $arr[] = $user_;
        }
    }
        // print_r($followers);
        // echo $check_it;
        // die;
        // echo "<br>";
        // echo "<pre>";
        // print_r($result);
        // die;
        if (!empty($result) && $check_it) {
           foreach($result as $deco){
            $userid_ = $deco['userid'];
            // echo $userid_;
            // print_r($followers);
            // die;
             if(in_array($userid_, $arr)){
                $sql = "SELECT read_notis FROM notifications WHERE userid = '$userid_'";
                $check_re = $DB->read($sql);
                if($check_re[0]['read_notis'] == '0'){
                    $unread = true;
                }else{
                    $unread = false;
                }
        }else{
            $unread = false;
        }
            if (!($unread == true)) {
                return false;
            } else {
                return true;
            }
        } 
        }else {
            return false;
        }
    }

    public function clear_notis($userid){
        $sql = "SELECT * FROM notifications";
        $DB = new Database();
        $result = $DB->read($sql);

        if(!empty($result)){
            foreach($result as $array){
                print_r($array);
            }
        }

    }
}
