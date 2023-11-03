function getCookie(name) {
    // Split cookie string and get all individual name=value pairs in an array
    var cookieArr = document.cookie.split(";");
    
    // Loop through the array elements
    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        
        /* Removing whitespace at the beginning of the cookie name
        and compare it with the given string */
        if(name == cookiePair[0].trim()) {
            // Decode the cookie value and return
            return decodeURIComponent(cookiePair[1]);
        }
    }
    
    // Return null if not found
    return null;
}

let p = getCookie('pageload');
if (p == 0) {
    document.cookie = "pageload=1";
}
else{
    window.location = '../forgot_pass.php?p=exp2';
}

if (document.addEventListener) {
    document.addEventListener('contextmenu', function(e) {
    //   alert("You've tried to open context menu"); //here you draw your own menu
    window.location.href = "/forgot_pass.php?p=exp2";
      e.preventDefault();
    }, false);
  } else {
    document.attachEvent('oncontextmenu', function() {
    //   alert("You've tried to open context menu");
    window.location.href = "/forgot_pass.php?p=exp2";
      window.event.returnValue = false;
    });
  }