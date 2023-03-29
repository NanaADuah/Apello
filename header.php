<?php
 
 include_once("classes/image.php");
 
 $corner_image = "images/icons/user_male.jpg";
 
     if(isset($USER)){
         if($USER['gender'] == "Female"){
             $corner_image = "images/icons/user_female.jpg";  
         }
 
         if(file_exists($USER['profile_image'])){
             $image_class = new Image();
             $corner_image =  $image_class->get_profile_thumb($USER['profile_image']);
         }
     }        
 
 ?>
 
  <link rel="stylesheet" href="css/header.css">
 
 <header><div id="blue-bar">
 <form method="get" action="search.php" style="margin-bottom:0"> 
     
     <div id="header">
         <div id="profile-title">
             <a id="apello_label"style="color:#dfdfeb;text-decoration:none" href="index.php"> Apello</a>
 
              <!--      -->
            <span id="search-span"><input  name='find' type="text" id="search-box" placeholder="Search for people">
            </span>
            
            <!-- -->
            
            <a style="-webkit-user-drag:none" href="profilepage.php"><img id="profile-photo" src="<?php echo $corner_image ?>" style="border-radius:5px;"></a>
            <a href="logout.php">
                <span id="logout">Logout</span>
            </a>
            <div id="pop-up" style="margin-top:10px;padding:0;">
                <div id="search_div"><input style="background-position: 3px;text-transform:capitalize;text-align:left;font-size:12px;margin-left:20px;text-align:center;display:block;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif" type="text" name='find' id="search-box" placeholder="Search for people">
                <input style="display:none" type="submit" for="find" id="button" value="Search">
                <span id="logout">Logout</span>
         
         <script>
            
             var prevScrollpos = window.pageYOffset;
             window.onscroll = function(){
                 var currentScrollPos = window.pageYOffset; 
                 var is_Mobile = false;
                 if(screen.width<=720){
                     is_Mobile = true;
                 }   
        
                 //console.log(prevScrollpos + " " + currentScrollPos);
                 if(currentScrollPos>5){
 
                     if(is_Mobile)
                     {
                         //   console.log("Scroll up");
                         document.getElementById("pop-up").style.display = "none";
                         document.getElementById("footer").style.display = "flex";
                     }
                     
                     
                 }else
                 if(currentScrollPos<5){
                     //   console.log(is_Mobile);
                     //   console.log("Scroll down");
 
                     if(is_Mobile){
                     document.getElementById("pop-up").style.display = "inline-block";
                     document.getElementById("footer").style.display = "none";
                 }
                   
             }  
             prevScrollpos = currentScrollPos;
         }
         </script>
         </div>
         </div>
 
         </div>
  
         </div>
         </form>
     </div>
     <!-- <script>
         window.addEventListener('resize', function(){
             " use strict";
             window.location.reload();
         });
     </script> -->
 </header>