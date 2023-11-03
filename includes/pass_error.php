<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CDAC CTF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/images/flag.png">
    <script src="js/block.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
  </head>
    <style>
      body{
          margin: 0;
          padding: 40px 0;
          text-align:center;
          background: linear-gradient(rgba(0,0,50,0.5),rgba(0,0,50,0.5)),url("/images/bg.png");
          /* background-size: cover; */
          background-position: top;
          font-family: sans-serif;
      }
      h1 {
        color: #ff6666;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-weight: 900;
        font-size: 40px;
        margin-bottom: 10px;
      }
      p {
        color: #404F5E;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-size:20px;
        margin: 0;
      }
      i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
      }
      .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
      }
    </style>
    <body>
      <div class="card">
        <div style="border-radius:200px; height:200px; width:200px; background: #ffffff; margin:0 auto;">
        <?php
          if (isset($_GET['p']) && $_GET['p'] == 'error') {
              echo '<i class="checkmark"><img src="/images/invalid.gif" alt="Success " style="width:216px;height:210px;"/></i>';
              echo '</div>';
              echo '<h1>Wrong Password</h1>';
              echo "<p>Old password is wrong<br/><b>Please try again....</b></p>";
              echo '<p><small><br>You will be redirected in.... <b><span id="counter">3</b></span> second(s)</small></p>';
          } else {
              header('Location: /login.php');
          } ?>
        </div>
    </body>
</html>

<script type="text/javascript">
  function countdown() {
      var i = document.getElementById('counter');
      if (parseInt(i.innerHTML)<=0) {
          location.href = '/dashboard.php?p=settings';
      }
      if (parseInt(i.innerHTML)!=0) {
          i.innerHTML = parseInt(i.innerHTML)-1;
      }
  }
  setInterval(function(){ countdown(); },1000);
</script>