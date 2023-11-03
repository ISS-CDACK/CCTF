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
        color: #0ac523;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-weight: 900;
        font-size: 40px;
        margin-bottom: 10px;
      }
      h2 {
        color: #fbd312;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-weight: 900;
        font-size: 40px;
        margin-bottom: 10px;
      }
      h3 {
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
          if (isset($_GET['p']) && $_GET['p'] == 'new_account') {
              echo '<i class="checkmark"><img src="/images/sus.gif" alt="Success " style="width:216px;height:210px;"/></i>';
              echo '</div>';
              echo '<h1>Success</h1>';
              echo "<p>Account created Successfully<br/> We will verify your account and active it shortly</p>";
              echo '<p><small><br>You will be redirected in.... <b><span id="counter">5</b></span> second(s)</small></p>';
          } elseif (isset($_GET['p']) && $_GET['p'] == 'changed') {
              echo '<i class="checkmark"><img src="/images/sus.gif" alt="Success " style="width:216px;height:210px;"/></i>';
              echo '</div>';
              echo '<h1>Success</h1>';
              echo '<p>Password changed Successfully<br/> Please login to your account... </p>';
              echo '<p><small><br>You will be redirected in.... <b><span id="counter">5</b></span> second(s)</small></p>';
          } elseif (isset($_GET['p']) && $_GET['p'] == 'not_active') {
              echo '<i class="checkmark"><img src="/images/warning.gif" alt="Success " style="width:216px;height:210px;"/></i>';
              echo '</div>';
              echo '<h2>Account under review</h2>';
              echo "<p>Your account is under review<br/> We will verify your account and active it shortly</p><p>Till then you can't login.. <b>Please wait</b></p>";
              echo '<p><small><br>You will be redirected in.... <b><span id="counter">10</b></span> second(s)</small></p>';
          } elseif (isset($_GET['p']) && $_GET['p'] == 'user_exits') {
              echo '<i class="checkmark"><img src="/images/warning.gif" alt="Success " style="width:216px;height:210px;"/></i>';
              echo '</div>';
              echo '<h2>Account Exists</h2>';
              echo '<p>Your account already exists<br/>Please login to your account.</p>';
              echo '<p><small><br>You will be redirected in.... <b><span id="counter">10</b></span> second(s)</small></p>';
          } elseif (isset($_GET['p']) && $_GET['p'] == 'login_failed') {
              echo '<i class="checkmark"><img src="/images/invalid.gif" alt="Success " style="width:216px;height:210px;"/></i>';
              echo '</div>';
              echo '<h3>Invalid Email/Password</h3>';
              echo '<p>You entered invalid Email/Password<br/><b>Please try again</b></p>';
              echo '<p><small><br>You will be redirected in.... <b><span id="counter">4</b></span> second(s)</small></p>';
          } elseif (isset($_GET['p']) && $_GET['p'] == 'key') {
              echo '<i class="checkmark"><img src="/images/invalid.gif" alt="Success " style="width:216px;height:210px;"/></i>';
              echo '</div>';
              echo '<h3>Invalid Email/Key</h3>';
              echo '<p>You entered invalid Email/Key<br/><b>For key Please contact to Admin</b></p>';
              echo '<p><small><br>You will be redirected in.... <b><span id="counter">7</b></span> second(s)</small></p>';
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
          location.href = '/login.php?p=login';
      }
      if (parseInt(i.innerHTML)!=0) {
          i.innerHTML = parseInt(i.innerHTML)-1;
      }
  }
  setInterval(function(){ countdown(); },1000);
</script>