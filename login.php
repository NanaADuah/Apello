<?php 

session_start();
//print_r( $_SESSION);
    include("classes/connect.php");
    include("classes/login.php");

    
        $email = "";
        $password = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){  
        if(($_POST['remain_logged']) == "1"){
            
            //ini_set('session.cookie_lifetime', '864000');   //ten days in sesssion
            setcookie("email", $_POST['email'], time()+(10*365*24*60*60));
            setcookie("password", hash("sha1",$_POST['password']), time()+(10*365*24*60*60));
    }
    
        $login = new Login();
        if(isset($_COOKIE['email']) || isset($_COOKIE['password']) || ($_POST['remain_logged']) == "1"){
        $result = $login->evaluate($_POST,1);
    }else{
        $result = $login->evaluate($_POST, 0);
    }

    if($result != ""){
        
        echo "<div style='text-align:center; font-size:12px;color:white;background-color:grey;'>";
        echo "<br>The following errors occurred:<br><br>";
        echo $result;
        echo "</div>";
    }else{

        header("Location: profilepage.php");
        die;
    }

        $password = $_POST['password'];
        $email = $_POST['email'];
}
 
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apello | Log In</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/master.css">
</head>

<body class="container">
    
    <div id="bar">
        <div id="heading">Apello</div> 
        <a href ="signup-2.php"><div  id="signup">Sign Up</div></a>
    </div>

    <div id="bar-2">
        <form method="post"> 
            <br style="clear:both">
            <span id="Log_text">Log in to Apello</span><br><br>
            <input  value="<?php echo $email ?>" name="email" type="text" id="text" placeholder="Email address" value="<?php if(!empty($_COOKIE['email'])){echo $_COOKIE['email'];} ?>" ><br><br>
            <input value="<?php echo $password ?>" name="password" type="password" id="text" placeholder="Password" value="<?php if(!empty($_COOKIE['password'])){echo $_COOKIE['password'];} ?>" ><br>
            <input type="hidden" name="remain_logged" value="0" >   
            <span id="Log_text"><input style="margin-top:10px;margin-bottom:10px" id="check_box"type="checkbox" name="remain_logged" value="1" >Remember Me</span><br>
            
                <input type="submit" id="button" value="Log In">
            </form>        
        </div>
        <!-- <div class="bubble" ></div>; -->
        <!-- <a href="https://www.SmarterASP.NET/index?r=nana321"><img src="https://www.SmarterASP.NET/affiliate/728X90.gif" style='border:none'></a> -->

    
</body>

</html>