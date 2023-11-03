<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CTF Time</title>
    <link rel="icon" href="/images/flag.png">
    <link rel="stylesheet" type="text/css" href="/css/showtime.css">
  </head>
  <body>

  <div class="area" >
            <ul class="circles">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
            </ul>

<br>
<br>
<br>

<?php
include 'includes/comp_time.php';

$t=time();
$start_time = $ctf_start_time;
$end_time = $ctf_end_time;

if ($t >= $start_time && $t <= $end_time){
    $comp_state = 'going';
}
elseif ($t <= $start_time){
    $comp_state = 'upcoming';
}
elseif ($t >= $end_time){
    $comp_state = 'end';
}


if ($comp_state == 'going'){ 

echo'<p class="txt-center m1-txt1 p-t-33 p-b-68">Competition Will End In</p>

<div class="wsize2 flex-w flex-c hsize1 cd100">
    <div class="flex-col-c-m size2 how-countdown">
        <span class="l1-txt1 p-b-9 days" id="days">0</span>
        <span class="s1-txt1">Days</span>
    </div>

    <div class="flex-col-c-m size2 how-countdown">
        <span class="l1-txt1 p-b-9 hours" id="hours">0</span>
        <span class="s1-txt1">Hours</span>
    </div>

    <div class="flex-col-c-m size2 how-countdown">
        <span class="l1-txt1 p-b-9 minutes" id="minutes">0</span>
        <span class="s1-txt1">Minutes</span>
    </div>

    <div class="flex-col-c-m size2 how-countdown">
        <span class="l1-txt1 p-b-9 seconds" id="seconds">0</span>
        <span class="s1-txt1">Seconds</span>
    </div>
</div>';
}

elseif ($comp_state == 'upcoming'){ 
    echo'<p class="txt-center m1-txt1 p-t-33 p-b-68">Competition Will Start In</p>
    <div class="wsize2 flex-w flex-c hsize1 cd100">
        <div class="flex-col-c-m size2 how-countdown">
            <span class="l1-txt1 p-b-9 days" id="days">0</span>
            <span class="s1-txt1">Days</span>
        </div>
    
        <div class="flex-col-c-m size2 how-countdown">
            <span class="l1-txt1 p-b-9 hours" id="hours">0</span>
            <span class="s1-txt1">Hours</span>
        </div>
    
        <div class="flex-col-c-m size2 how-countdown">
            <span class="l1-txt1 p-b-9 minutes" id="minutes">0</span>
            <span class="s1-txt1">Minutes</span>
        </div>
    
        <div class="flex-col-c-m size2 how-countdown">
            <span class="l1-txt1 p-b-9 seconds" id="seconds">0</span>
            <span class="s1-txt1">Seconds</span>
        </div>
    </div>';
    }

elseif ($comp_state == 'end'){
    echo'<p class="txt-center m1-txt1 p-t-33 p-b-68" style="padding-top: 260px;font-size: 40px;">Competition Has Ended</p>';
}
?>


<script>

let flag = '<?php echo $comp_state; ?>';

if (flag == 'upcoming'){ 
    var countDownDate2 = <?php echo $ctf_start_time;?> * 1000;
    var php_time2 = <?php date_default_timezone_set('Asia/Kolkata');echo (time()*1000);?>;

    var x = setInterval(function() {

    now2 = php_time2;
    php_time2 = php_time2+1000;

    var distance = countDownDate2 - now2;

    // Time calculations for days, hours, minutes and seconds
    var days2 = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours2 = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes2 = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds2 = Math.floor((distance % (1000 * 60)) / 1000);


    try{ 
        document.getElementById("days").innerHTML = days2;
        document.getElementById("hours").innerHTML = hours2 ;
        document.getElementById("minutes").innerHTML = minutes2;
        document.getElementById("seconds").innerHTML = seconds2;
    }
    catch (this_error){

    }
    if (distance < 0) {
        clearInterval(x);
        window.location.replace("/showtime.php");
    }

    }, 1000);
} else if (flag == 'going'){ 
    var countDownDate2 = <?php echo $ctf_end_time;?> * 1000;
    var php_time = <?php date_default_timezone_set('Asia/Kolkata');echo (time()*1000);?>;

    var x = setInterval(function() {

	now2 = php_time;
	php_time = php_time+1000;

    var distance = countDownDate2 - now2 ;

    // Time calculations for days, hours, minutes and seconds
    var days2 = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours2 = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes2 = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds2 = Math.floor((distance % (1000 * 60)) / 1000);

    try{ 
        document.getElementById("days").innerHTML = days2;
        document.getElementById("hours").innerHTML = hours2 ;
        document.getElementById("minutes").innerHTML = minutes2;
        document.getElementById("seconds").innerHTML = seconds2;
    }
    catch (this_error){

    }
    if (distance < 0) {
        clearInterval(x);
        window.location.replace("/showtime.php");
    }
    }, 1000);
}
</script>
</div >
</body>
</html>
