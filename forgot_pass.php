<?php
  session_start();
  if (isset($_SESSION['login_user'])) {
      header('Location: login.php');
      die();
  }

  if (isset($_COOKIE['key'])) {
      unset($_COOKIE['key']);
      setcookie('key', '', time() - 3600, '/password_reset');
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CCTF</title>
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
        color: #ff4d4d;
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
          if (isset($_GET['p']) && $_GET['p'] == 'read') {
              echo '<i class="checkmark"><img src="/images/stop.gif" alt="Success " style="width:216px;height:210px;"/></i>';
              echo '</div>';
              echo '<h1>Stop and Read Carefully</h1>';
              echo '<p>You need to contact admin for key<br/> Using that key you can reset your password</p><p>the key will valid for one time use only';
              echo '<p><small><br>You will be redirected in.... <b><span id="counter">15</b></span> seconds(s)</small></p>';
          } elseif (isset($_GET['p']) && $_GET['p'] == 'exp') {
              echo '<i class="checkmark"><img src="/images/timeout.gif" alt="Success " style="width:216px;height:210px;"/></i>';
              echo '</div>';
              echo '<h2>Session Expired</h2>';
              echo '<p>Your session is expired<br/><b>Please try again</b></p>';
              echo '<p><small><br>You will be redirected in.... <b><span id="counter">10</b></span> seconds(s)</small></p>';
          } elseif (isset($_GET['p']) && $_GET['p'] == 'exp2') {
              echo '<i class="checkmark"><img src="/images/stop.gif" alt="Success " style="width:216px;height:210px;"/></i>';
              echo '</div>';
              echo '<h1>Forbiden Action</h1>';
              echo '<p>Refrashe & Right Click is not allowed<br/><b>Please Dont refrashe or right click in this page</b></p>';
              echo '<p><small><br>You will be redirected in.... <b><span id="counter">6</b></span> seconds(s)</small></p>';
          } else {
              header('Location: /login.php');
          }
        ?>
      </div>
    </body>
</html>

<script type="text/javascript">
  function countdown() {
    var i = document.getElementById('counter');
    if (parseInt(i.innerHTML)<=0) {
      location.href = '/password_reset/pass_reset.php?p=mail';
    }
    if (parseInt(i.innerHTML)!=0) {
      i.innerHTML = parseInt(i.innerHTML)-1;
    }
  }
  setInterval(function(){ countdown(); },1000);
</script>