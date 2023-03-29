<div id="settings-content" style="width: 100%; padding-top:10px;margin-top:5px;margin-bottom:10px;min-height: 400px;text-align:center;display:flex">
    <div style="padding:20px; max-width:350px; display:inline-block;flex:1" >
        <form method="post" enctype="multipart/form-data">
       
        <?php

            $settings_class = new Settings();

            $settings = $settings_class->get_settings($_SESSION['apello_userid']);

            if(is_array($settings)){
            echo "<input type='text' id='text_box' name='first_name'  value='". htmlspecialchars($settings['first_name']) ."' placeholder='First Name'>";
            // echo "<input type='text' id='text_box' name='username'  value='". htmlspecialchars($settings['username']) ."' placeholder='Username'>";
            echo "<input type='text' id='text_box' name='last_name' value='" .htmlspecialchars($settings['last_name']) . "' placeholder='Last Name'>";

            echo "<select value='". htmlspecialchars($settings['gender']). "' style='margin-top:25px;height:30px;width:80%;border-radius:5px;border: solid thin gray;font-size:14px'>
                        <option>Male</option>
                        <option>Female</option>
                  </select>";
            
//             echo "<selct id='text_box' name='email' style='height:30px' value='$settings[gender]'>
//             <option>Male</option>
//             <option>Female</option>
//       </select>
// ";
            echo "<input type='text' id='text_box' name='email' value='". htmlspecialchars($settings['email']) ."'  placeholder='Email'>";
            echo "<input type='password' id='text_box' name='password' value='". htmlspecialchars($settings['password']) ."' placeholder='Password'>";
            echo "<input type='password' id='text_box' name='password2' value='". htmlspecialchars($settings['password']) ."' placeholder='Retype password'>";
            echo "<br><br><span id='about-me'>About Me:</span>
            
                    <textarea id='text_box' style='margin-top:5px;height:100px;' name='about' >". htmlspecialchars($settings['about']) ."</textarea>";

        
            echo "<input type='submit' id='save_button' value='Save'>";
            
        }

        ?> 
        </form>
    </div>
    <div id="div_div" style="height:auto;flex:1;padding:20px;border-left:3px solid black">
    <div style="padding:2.5px;background: #555555;border-radius:20px"><a id="change-profile-text" href="change-profile-image.php?change=profile">Change Profile Image</a></div><br>
    <div style="padding:2.5px;background: #555555;color: white;border-radius:20px"><a id="change-profile-text" href="change-profile-image.php?change=cover">Change Profile Cover</a></div>
    <div  style='text-decoration:none;margin:auto;margin-top:20px;padding:2.5px;background: #555555;color: white;border-radius:20px;width:100px;'><a id='change-profile-text' href="help.php"><div'>Help</div></a></div>
    </div>
</div>