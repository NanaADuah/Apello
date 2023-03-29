<?php

class Settings{

    public function get_settings($id){

            $DB = new Database();
            $sql = "SELECT * FROM users WHERE userid = '$id' LIMIT 1";

            $row = $DB->read($sql);


            if(is_array($row)){
                return $row[0];
            }

    }

    public function save_settings($data, $id){

        $password = $data['password'];
        $DB = new Database();
        
        if(strlen($password) < 30){

            if($data['password'] == $data['password2']){
                $data['password'] = hash("sha1", $password);
            }else{
                unset($data['password']);
            }
        }
        
        unset($data['password2']);
        $sql = "UPDATE users SET ";
        foreach($data as $key => $value){
            
            $sql .= $key . "='" .addslashes($value). "',"  ;
        }
        
        $sql = trim($sql, ",");
        $sql .= " WHERE userid = '$id' LIMIT 1 ";

        $DB->save($sql);
    }
    
}
