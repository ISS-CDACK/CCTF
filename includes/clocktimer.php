
<link rel="stylesheet" type="text/css" href="/css/timer.css">
<?php
if ($comp_state == 'upcoming'){ 
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


var countDownDate2 = <?php echo $ctf_start_time;?> * 1000;
var php_server_time = <?php date_default_timezone_set('Asia/Kolkata');echo (time()*1000);?>

var x = setInterval(function() {

    var now2 = php_server_time;
    php_server_time = php_server_time+1000;

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
    window.location.replace("/dashboard.php?p=challenges");
    // document.getElementById("demo").innerHTML = "EXPIRED";
}

}, 1000);

</script>
