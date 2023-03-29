<?php 
    include("classes/connect.php");
    include("classes/signup.php");
        $first_name = "";
        $last_name = "";
        $gender = "";
        $date_birth ="";
        $email = "";
        $username = "";
        $location = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){  
        $signup = new SignUp();
        $result = $signup->evaluate($_POST);

    if($result != ""){
        echo "<div style='text-align:center; font-size:12px;color:white;background-color:grey;'>";
        echo "<br>The following errors occurred:<br><br>";
        echo $result;
        echo "</div>";
    }else{

        header("Location: login.php");
        die;
    }

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $date_birth = $_POST['birthday'];
        $location = $_POST['user_location'];
}

   
?>
<script>

    function nospace(t){
        if(t.value.match(/\s/g)){
            t.value = t.value.replace(/\s/g,'');
        }
    }

    function blockSpecialChars(e){
        if(e.value.match(/\s/g)){
            e.value = e.value.replace(/\s/g,'');
        }
        // console.log('your face');
        return /^[A-Za-z0-9._]/.test(e);
        // console.log(e);
    }
</script>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apello | Sign Up</title>
    <link rel="stylesheet" href="css/signup-2.css">
    <link rel="stylesheet" href="css/master.css">
</head>

<body class="container">
    <div id="bar">
        <div id="heading">Apello</div>
        <a href="login.php"><div id="login">Log In</div></a>
    </div>

    <div id="bar-2">
        <form method="post" action="">
            <br>Log in to Apello<br><br>
            <div id="main_1">
                <div id="holder" style='flex:1'>
            <?php
              
            ?>
            <label for="first">First Name:</label><br>
            <input value="<?php echo $first_name ?>" class='first' name="first_name" type="text" id="text" placeholder="First Name"><br><br>

            <label for="surname">Surname:</label><br>
            <input value="<?php echo $last_name ?>" class='surname' name="last_name" type="text" id="text" placeholder="Last Name"><br><br>

            <label for="email_address">Email Address:</label><br>
            <input  value="<?php echo $email ?>" class='email_address' name="email" type="text" id="text" placeholder="Email address"><br><br>

            <label for="surname">Preferred username:</label><br>
            <input value="<?php echo $username ?>" class='user_name' name="username" type="text" id="text" placeholder="Preferred username" onkeyup="blockSpecialChars(this)"><br><br>
            
            
            <label for="pass_1">Password:</label><br>
            <input name="password" type="password" class='pass_1' id="text" placeholder="Password"><br><br>

            <label for="pass_2">Confirm password:</label><br>
            <input name="password2" type="password" class='pass_2' id="text" placeholder="Retype Password"><br><br>
            <br style="clear:both">

            </div>

            <div style='flex:1'>
            <span  >Gender:</span><br>

            <select id="gender_text" style="height:30px" name="gender" id="text">
                <option>Male</option>
                <option>Female</option>
            </select>
            <br><br>

            <label for="location_holder">Current Location:</label><br>
            <input value="<?php echo $location ?>" name="user_location" type="text" class='location_holder' id="text" placeholder="Your current location"><br><br>

            <script>
                document.getElementById('date_holder').value = new Date().toDateInputValue();
            </script>

            <label for="birthday">Birthday:</label><br>
            <input type="date" class='birthday' name='birthday'  id='date_holder' placeholder="Date of birth" value="<?php echo $date_birth?>">

            <br> <br style="clear:both">
            </div>
            
            </div>
            <input type="submit" id="button" value="Sign Up">
            <br style="clear:both">
        </form>
    </div>
</body>

</html>