
var countDownDate = ctf_start_js;
var server_time_st = php_server_time ;


// new Date("Aug 25, 2022 16:37:52").getTime();
function countdown() {
    var now = server_time_st ;  
    server_time_st  =  server_time_st +1000;
    var timeleft = countDownDate - now;
    var days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
    var hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((timeleft % (1000 * 60)) / 1000);
    // document.getElementById("days").innerHTML = days + "d "

    try {
    document.getElementById("hours").innerHTML = hours +  "h : " 
    document.getElementById("mins").innerHTML = minutes + "m : " 
    document.getElementById("secs").innerHTML = seconds + "s "
    }
    catch(err){
    }

    if (timeleft < 0) {
        window.location.replace("/dashboard.php?p=challenges");
    }
}

setInterval(function(){ countdown(); },1000);
