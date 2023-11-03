if (document.addEventListener) {
    document.addEventListener('contextmenu', function(e) {
    //   alert("You've tried to open context menu"); //here you draw your own menu
    window.location.href = "/error.html";
      e.preventDefault();
    }, false);
  } else {
    document.attachEvent('oncontextmenu', function() {
    //   alert("You've tried to open context menu");
    window.location.href = "/error.html";
      window.event.returnValue = false;
    });
  }