<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ratings | Apello</title>
    <link rel="stylesheet" href="css/rating.css">
</head>
<body>
    <div id="main">
        
        <!-- <form method="rating"> -->
            <div id="options-div">
            <span id="heading_title">Would you mind taking a moment to rate us?</span>
            <br>
            <div id="rating_box">
                <div class="box_text" id="1" value="1" name="rating" onclick="document.getElementById('1').style.background='#253c88'; DisableRest('1')" ><span>1</span></div>
                <div class="box_text" id="2" value="2" name="rating" onclick="document.getElementById('2').style.background='#253c88'; DisableRest('2')"><span>2</span></div>
                <div class="box_text" id="3" value="3" name="rating" onclick="document.getElementById('3').style.background='#253c88'; DisableRest('3')"><span>3</span></div>
                <div class="box_text" id="4" value="4" name="rating" onclick="document.getElementById('4').style.background='#253c88'; DisableRest('4')" ><span>4</span></div>
                <div class="box_text" id="5" value="5" name="rating" onclick="document.getElementById('5').style.background='#253c88'; DisableRest('5')"><span>5</span></div>
            </div>
            
            <button id="done" onclick="get_Selection()">DONE</button>
<script> 
var rating = 0;

function get_Selection(){
    document.getElementById('options-div').style.display = 'none';
    document.getElementById('thank-you').style.display =  'block';
}


function DisableRest(id){
    var check;
    // console.log(id);
    check = id;
    if(id=='1'){
        document.getElementById(2).style.background='#405dbd';
        document.getElementById(3).style.background='#405dbd';
        document.getElementById(4).style.background='#405dbd';
        document.getElementById(5).style.background='#405dbd';
        rating = '1';
    }else
    if(id=='2'){
        document.getElementById(1).style.background='#405dbd';
        document.getElementById(3).style.background='#405dbd';
        document.getElementById(4).style.background='#405dbd';
        document.getElementById(5).style.background='#405dbd';
        rating= '2';
    }else
    if(id=='3'){
        document.getElementById(1).style.background='#405dbd';
        document.getElementById(2).style.background='#405dbd';
        document.getElementById(4).style.background='#405dbd';
        document.getElementById(5).style.background='#405dbd';
        rating='3';
    }else
    if(id=='4'){
        document.getElementById(1).style.background='#405dbd';
        document.getElementById(2).style.background='#405dbd';
        document.getElementById(3).style.background='#405dbd';
        document.getElementById(5).style.background='#405dbd';
        rating='4';
    }else
    if(id=='5'){
        document.getElementById(1).style.background='#405dbd';
        document.getElementById(2).style.background='#405dbd';
        document.getElementById(3).style.background='#405dbd';
        document.getElementById(4).style.background='#405dbd';
        rating='5';
    }else{
    if(check=='null' || check==''){
        return rating;
        }
    }

}


            
</script>

        </div>
        <div id="thank-you">
            <span id="thank-text">Thank you<br> Enjoy using Apello!</span>
        </div>
       
        <!-- </form> -->
    </div>


</body>
</html>