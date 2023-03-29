<?php

class SignUp{

    private $error = "";
    public function evaluate($data){
        foreach ($data as $key => $value){
            if(empty($value)){
               $this->error = $this->error . $key . " is empty!<br>";
            }

            if($key == "email"){
               if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $value)){
                    $this->error = $this->error . "Invalid email address! <br>";
               }

               if($key == "email"){
                //    echo $key;
                   $sql = "SELECT * FROM users WHERE email = '$value' LIMIT 1";
                   $DB = new Database();
                //    echo $sql;
                   $result = $DB->read($sql);
                //    print_r($result);
                   if(!empty($result)){
                    $this->error = $this->error . "Email address is already in use! <br>";                       
                   }

               }
              
            }

           

            if($key == "first_name"){
                   
                if (is_numeric($value)){
                    $this->error = $this->error . "Invalid first name <br>";
               }
              
            }
             if($key == "last_name"){
                if (is_numeric($value)){
                     $this->error = $this->error . "Invalid last name <br>";
                }
               
             }
           
           

        }
         //  print_r($data);
            // print_r($data);
            if(isset($data['password']) && isset($data['password2'])){
                // echo "yes";
                // echo $data['password'];
                // echo "<br>";
                // echo $data['password2'];
                if(($data['password']) !== ($data['password2'])){
                    // echo "not equal";
                    $this->error = $this->error . "Passwords do not match <br>";
                }
                    // die;
            }

            if(isset($data['username'])){
                // echo "yes";
                // die;
                $user_name = $data['username'];
                $sql = "SELECT * FROM users WHERE username = '$user_name' LIMIT 1";
                $DB = new Database();
                // echo $sql;
                // echo "<br>";
                $result = $DB->read($sql);
                // print_r($result);
                // die;
            if(!empty($result)){
                $this->error = $this->error . "Username already exists, please choose another one! <br>";
            }
         }
        if($this->error == ""){
            // no error
            $this->create_user($data);
        }
        else{
            return $this->error . "<br>";
        }

        
    }

    public function cleanUsername($string){
        
    }

    public function create_user($data){

            $first_name = ucfirst($data['first_name']);
            $last_name = ucfirst($data['last_name']);
            $gender = $data['gender'];
            $email = $data['email'];
            $password = hash("sha1",$data['password']);
            $url_address = strtoLower($first_name) . "." . strtoLower($last_name);
            $sql = "SELECT * FROM users WHERE url_address = '$url_address' LIMIT 1";
            $DB = new Database();
            $result = $DB->read($sql);
            if(!empty($result)){
                $length = rand(1,3);
                $url_address =  $url_address . $length;
            }

            $userid = $this->create_userid();
            $about = addslashes("Hey there, I'm using Apello!");
            
            $value = strtolower($data['username']);
            $value = str_replace(' ', '-', $value);
            $username = preg_replace('/[^A-Za-z0-9\-]/', '', $value);
            // $username = $data['username']);
            $D0B = $data['birthday'];
            $location = ucfirst($data["user_location"]);

            $query = "INSERT INTO users (userid, first_name, last_name, gender, email, password, url_address, about, username, dob, location) VALUES ($userid, '$first_name', '$last_name', '$gender', '$email', '$password', '$url_address', '$about', '$username', '$D0B', '$location')";
            $DB = new Database();
            $DB -> save($query);
    }


    private function create_userid(){

        $length = rand(4,10);
        $number = "";
        for ($i=0; $i < $length; $i++){
            $new_rand = rand(0,9);
            $number = $number . $new_rand;

        }
        return $number;
    }
}
