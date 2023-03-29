<div id="settings-content" style="width: 100%;padding-bottom:20px; padding-top:0px;margin-top:5px;margin-bottom:10px;height:auto;text-align:center">
    <div style="padding:10px; max-width:400px; display:inline-block;" >
        <form method="post" enctype="multipart/form-data">
       
        <?php

            $settings_class = new Settings();

            $settings = $settings_class->get_settings($_SESSION['apello_userid']);

            if(is_array($settings)){
            
            echo "<br> <div id='about_box'> About Me:</div>
            
                    <div id='text_box1' style='border:none;margin-top:5px;height:100px;' name='about' >". htmlspecialchars($settings['about']) ."</div>";

            
            }

        ?> 
        </form>
    </div>
    
</div>