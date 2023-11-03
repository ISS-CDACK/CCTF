<?php 
// include '../admin_session.php';
// include '../config.php';

$total_key = 0;
$key_used = 0;
$pending_key = 0;


$sql = "select count(*) as count from special_keys";
$result = mysqli_query($conn, $sql) or die(mysqli_error());
if (!$result) echo "ERROR";
$count = mysqli_num_rows($result);
if ($count > 0) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $total_key = $row['count'];
}

$sql = "select count(*) as count from special_keys WHERE status = 'true'";
$result = mysqli_query($conn, $sql) or die(mysqli_error());
if (!$result) echo "ERROR";
$count = mysqli_num_rows($result);
if ($count > 0) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $key_used = $row['count'];
}

$sql = "select count(*) as count from special_keys WHERE status = 'false'";
$result = mysqli_query($conn, $sql) or die(mysqli_error());
if (!$result) echo "ERROR";
$count = mysqli_num_rows($result);
if ($count > 0) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $pending_key = $row['count'];
}

?>


<style>
/* The snackbar - position it at the bottom and in the middle of the screen */
#snackbar {
  visibility: hidden; 
  /* Hidden by default. Visible on click */
  min-width: 250px; /* Set a default minimum width */
  margin-left: -125px; /* Divide value of min-width by 2 */
  background-color: #f70d1a; /* Black background color */
  color: #fff; /* White text color */
  text-align: center; /* Centered text */
  border-radius: 2px; /* Rounded borders */
  padding: 16px; /* Padding */
  position: fixed; /* Sit on top of the screen */
  z-index: 1; /* Add a z-index if needed */
  left: 50%; /* Center the snackbar */
  bottom: 30px; /* 30px from the bottom */
}

/* Show the snackbar when clicking on a button (class added with JavaScript) */
#snackbar.show {
  visibility: visible; /* Show the snackbar */
  /* Add animation: Take 0.5 seconds to fade in and out the snackbar.
  However, delay the fade out process for 2.5 seconds */
  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
  animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

/* Animations to fade the snackbar in and out */
@-webkit-keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 30px; opacity: 1;}
}

@keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
  from {bottom: 30px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
  from {bottom: 30px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}
</style>

<script>
function showerrorToast() {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
    }
</script>

<body>
    <div class="admin-dashboard" style='margin:65px 0 0 0'>

    <div class="container">
    <div class="settings" style='width: 78%'>
    <form id='gen-key' action="./controllers/gen_key.php" method="POST">
        <label>Enter Email</label>
        <input type="email" name="email"  required minlength="4" required readonly
     onfocus="this.removeAttribute('readonly');" autocomplete="off">
        <input type="submit" name="gen-key" value="Generate">
    </form>
</div>
</div>

    <div class="dash-row">
        <div class="col">
            <h3>Total Key Gereneret</h3>
            <h1><?php echo $total_key ?></h1>
        </div>
        <div class="col">
            <h3>Key Used</h3>
            <h1><?php echo $key_used ?></h1>
        </div>
        <div class="col">
            <h3>Keys not used</h3>
            <h1><?php echo $pending_key ?></h1>
        </div>
        
    </div>
    <div class="container">
    <div class="toolbar">
            <h3>Key History</h3>
        </div>
        <table>
            <tr class="head">
                <th>Username</th>
                <th>Key</th>
                <th>Used at</th>
            </tr>
            <?php 
                $sql = "select @a:=@a+1 as sl_no, u.name as name, u.email as mail, sb.key_value as kv, sb.use_time as ut, sb.status as st from (SELECT @a:= 0) AS a, users as u, special_keys as sb where sb.user_id = u.id ORDER BY sb.key_id DESC";
                $result = mysqli_query($conn, $sql) or die(mysqli_error());
                $count = mysqli_num_rows($result);
                // die(echo (implode(',',$result)));
                if ($count > 0) {
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        echo "<tr class='content'>";
                        // <small> [block]</small>
                        //die($row["name"]);
                        if ($row["st"] == 'true'){
                            echo "<td><s>".$row["name"]."</s></td>";
                            echo "<td><s>".$row["kv"]."</s> <small style='background-color:tomato;'>[USED]</small></td>";
                            echo "<td><s>".$row["ut"]."</s></td>";
                        }else{
                            echo "<td>".$row["name"]."</td>";
                            echo "<td>".$row["kv"]."</td>";
                            echo "<td>Not Used</td>";
                        }
                        echo "</tr>";
                    }
                }
            ?>
        </table>
            </div>
            <style>
    .last-update{ 
  position: relative;     
  bottom: 0px; 
  width: 100%;
  color: #27914c;
  font-size: 14.8px;
    /* font-weight: bold; */
  /* padding-top: 2%; */
}
</style>

<?php
$date = date_default_timezone_set('Asia/Kolkata');
$today = date("F j, Y, g:i a");
// ,(time() - 60 * 2));
?>

<div class="last-update" style='text-align: center; padding-top:20px;'>Last Update at <span style="font-weight: bold;"> <?php echo $today ?> </span></div>

            </div>
            
            </div>
            <?php
            if ( isset($_GET["err"]) && $_GET["err"] == 'mismatch' ){ 
                echo '<div id="snackbar">Email ID is not Registerd</div>';
                echo'<script>showerrorToast()</script>';
            }
            elseif ( isset($_GET["err"]) && $_GET["err"] == 'key_active' ){
                echo '<div id="snackbar">A key already exitsts for this user</div>';
                echo'<script>showerrorToast()</script>';
            }
            ?>
            
</body>


<?php
    $url1= '/admin/sudozone.php?p=keypage';
    // $_SERVER['REQUEST_URI'];
    header("Refresh: 60; URL=$url1");
?>