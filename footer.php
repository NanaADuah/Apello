<div id="footer">
    <div id="back-blur">
        <a id="options_footer" href="profilepage.php?id=<?php echo $user_data['userid'] ?>"><div id="options_footer" style="background:transparent;color: white"><img id="svg" src="images/footer/Home.svg"></div></a>
        <a id="options_footer" href="apello_friends.php"><div id="options_footer" style="background:transparent;color: white"><img id="svg" src="images/footer/Users.svg"></div></a>
        <a id="options_footer" href="profilepage.php?section=photos&id=<?php echo $user_data['userid']?>"><div id="options_footer" style="background:transparent;color: white"><img id="svg" src="images/footer/Photos.svg"></div></a>
        <a id="options_footer" href="profilepage.php?section=settings"><div id="options_footer" style="background:transparent;color: white"><img id="svg" src="images/footer/Settings.svg"></div></a>
        <a id="options_footer" href="status.php"><div id="options_footer" style="background:transparent;color: white"><img id="svg" src="images/footer/Status.svg"></div></a>
    </div>

    <script>
        let is_Mobile = "0";
        let indiv_width = 0;

        if (screen.width < 720) {
            is_Mobile = "1";
        } else {
            if (screen.width > 720) {
                is_Mobile = "0";
            }
        }

        if (is_Mobile == "1") {
            if (screen.width <= 720) {
                document.getElementById("footer").style.display = "flex";
                //indiv_width == (screen.width/25);
                // console.log(indiv_width);
                //document.getElementById("bar-options").style.width == indiv_width;
            }
        } else {
            // alert("Screen size not eligibile for a footer");
            document.getElementById("footer").style.display = "none";
        }
    </script>

</div>