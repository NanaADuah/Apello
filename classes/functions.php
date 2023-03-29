<?php

function check_tags($text)
{
    $words = explode(" ", $text);
    $str = "";
    $DB = new Database();
    if (is_array($words) && count($words) > 0) {
        foreach ($words as $word) {

            if (preg_match("/@[a-zA-Z_0-9\Q,!.\E]+/", $text)) {
                $tag_name = esc(trim($word, '@,!.'));
                $query = "SELECT * FROM users WHERE username = '$tag_name' LIMIT 1";
                $user_row = $DB->read($query);
                if (is_array($user_row)) {
                    $user_row = $user_row[0];
                    $str .= "<a id='user-tag' href='profilepage.php?id=$user_row[userid]'>" . $word . "</a>";
                } else {
                    $str .= htmlspecialchars($word) . " ";
                }
            } else {
                $str .= htmlspecialchars($word) . " ";
            }
        }
    }
    if(!empty($str)){
        return $str;
    }else{
        return htmlspecialchars($text);

    }
}

function esc($value)
{

    return addslashes($value);
}

function pagination_link()
{

    

    $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page_number = ($page_number <= 0) ? 1 : $page_number;

    $arr = array();
    $arr['prev_page'] = ''; 
    $arr['next_page'] = ''; 

    //get current url
    $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
    $url .= "?";

    $next_page_link = $url;
    $prev_page_link = $url;
    $page_found = false;

    $num = 0;

    foreach ($_GET as $key => $value) {
        $num++;
        if ($num == 1) {
            if ($key == "page") {
                $next_page_link .= $key . "=" . ($page_number + 1);
                $prev_page_link .= $key . "=" . ($page_number - 1);
                $page_found = true;
            } else {
                $next_page_link .= $key . "=" . $value;
                $prev_page_link .= $key . "=" . $value;
            }
        } else {
            if ($key == "page") {
                $next_page_link .= "&" . $key . "=" . ($page_number + 1);
                $prev_page_link .= "&" . $key . "=" . ($page_number - 1);
                $page_found = true;
            } else {
                $prev_page_link .= "&" . $key . "=" . $value;
                $next_page_link .= "&" . $key . "=" . $value;
            }
        }
    }

    
    $arr['next_page'] = $next_page_link; 
    $arr['prev_page'] = $prev_page_link; 

    if(!$page_found){

        $arr['prev_page'] = $prev_page_link . "&page=1"; 
        $arr['next_page'] = $next_page_link . "&page=2"; 
    
    }

    return $arr;
}
