<?php

class Login
{

    private $error = "";

    public function evaluate($data, $remain_logged = "0")
    {

        $email = addslashes($data['email']);
        $password = addslashes($data['password']);

        $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {

            $row = $result[0];

            if ($remain_logged == "1" || (!empty($_COOKIE['email']) && !empty($_COOKIE['password']))) {

                if (hash("sha1", $password) == $row['password']) {
                    //create a session data
                    $_SESSION['apello_userid'] = $row['userid'];
                } else {
                    $this->error .= "Invalid credentials..<br>";
                }
            } else {
                if ($this->hashed_text($password) == $row['password']) {
                    //create a session data
                    $_SESSION['apello_userid'] = $row['userid'];
                } else {
                    $this->error .= "Invalid credentials...<br>";
                }
            }
        } else {
            $this->error .= "Invalid credentials...<br>";
        }
        return $this->error;
    }


    public function check_login($id)
    {

        if (is_numeric($id)) {
            $query = "SELECT * FROM users WHERE userid = '$id' LIMIT 1";

            $DB = new Database();
            $result = $DB->read($query);

            if ($result) {
                $user_data = $result[0];
                return $user_data;
            } else {
                header("Location: login.php");
                die;
            }
        } else {
            header("Location: login.php");
        }
    }

    private function hashed_text($text)
    {


        $text = hash("sha1", $text);
        return $text;
    }
}
