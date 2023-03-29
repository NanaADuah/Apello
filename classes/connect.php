<?php

class Database{
    
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "apello_db";

    function connect_to_db(){

        $connection = mysqli_connect($this->host,$this->username, $this->password, $this->db);
        return $connection;

    }

    function read($query){
        $conn = $this->connect_to_db();
        $result = mysqli_query($conn, $query);

        if(!$result){
            return false;

        }else{
            $data = false;
         while($row = mysqli_fetch_assoc($result)){
             $data[] = $row;
    }
    return $data;
    }
    }

    function save($query){  
        $conn = $this->connect_to_db();
        $result = mysqli_query($conn, $query);

        if(!$result){
            return false;
        }else{
            return true;
        }
    }
}

