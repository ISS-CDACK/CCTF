<?php 

    $admin_user_count = 0;
    $admin_pending_user_count = 0;
    $admin_challenge_count = 0;
    $admin_cat_count = 0;

    $sql = "select count(*) as count from users WHERE role = 'user' AND status = 'true'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error());
    if (!$result) echo "ERROR";
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $admin_user_count = $row['count'];
    }

    $sql = "select count(*) as count from users WHERE role = 'user' AND status = 'false'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error());
    if (!$result) echo "ERROR";
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $admin_pending_user_count = $row['count'];
    }

    $sql = "select count(*) as count from challenges";
    $result = mysqli_query($conn, $sql) or die(mysqli_error());
    if (!$result) echo "ERROR";
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $admin_challenge_count = $row['count'];
    }

    $sql = "select count(*) as count from category";
    $result = mysqli_query($conn, $sql) or die(mysqli_error());
    if (!$result) echo "ERROR";
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $admin_cat_count = $row['count'];
    }
?>

<div class="admin-dashboard">
    <div class="dash-row">
        <div class="col">
            <h3>Verified Users</h3>
            <h1><?php echo $admin_user_count ?></h1>
        </div>
        <div class="col">
            <h3>Pending Users</h3>
            <h1><?php echo $admin_pending_user_count ?></h1>
        </div>
        <div class="col">
            <h3>Challenges</h3>
            <h1><?php echo $admin_challenge_count ?></h1>
        </div>
        <div class="col">
            <h3>categories</h3>
            <h1><?php echo $admin_cat_count ?></h1>
        </div>
    </div>
    <div class="container">
    <div class="toolbar">
            <h3>Recent Solves</h3>
        </div>
        <table>
            <tr class="head">
                <th>Name</th>
                <th>Challenge</th>
                <th>Score</th>
            </tr>

            <?php 

                $sql = "select @a:=@a+1 as sl_no, u.name as name, u.status as status, ch.title as title, ch.score as score from (SELECT @a:= 0) AS a, users as u, challenges as ch, scoreboard as sb where sb.c_id = ch.id and sb.user_id = u.id order by sb.ts asc";
                $result = mysqli_query($conn, $sql) or die(mysqli_error());
                $count = mysqli_num_rows($result);
                if ($count > 0) {
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        echo "<tr class='content'>";
                        // <small> [block]</small>
                        if ($row["status"] == 'false'){
                            echo "<td><s>".$row["name"]." <small style='background-color:tomato;'>[blocked]</small></s></td>";
                            echo "<td><s>".$row["title"]."</s></td>";
                            echo "<td><s>".$row["score"]."</s></td>";
                        }else{
                            echo "<td>".$row["name"]."</td>";
                            echo "<td>".$row["title"]."</td>";
                            echo "<td>".$row["score"]."</td>";
                        }
                        echo "</tr>";
                    }

                }

            ?>
        </table>
    </div>


    <?php
$date = date_default_timezone_set('Asia/Kolkata');
$today = date("F j, Y, g:i a");
// ,(time() - 60 * 2));
?>

<div class="last-update" style='text-align: center; padding-top:20px;'>Last Update at <span style="font-weight: bold;"> <?php echo $today ?> </span></div>

<script src="/js/jquery-3.2.1.min.js"></script>
<script>
setInterval("yourAjaxCall()",40000);
function yourAjaxCall(){
    $.ajax({    //create an ajax request to display.php
        type: "GET",
        url: "../get_user_state.php",             
        dataType: "html",   //expect html to be returned                
        success: function(response){  
            // $("#responsecontainer").html(response);
            text = response;
            string = text.replace(/^(?=\n)$|^\s*|\s*$|\n\n+/gm, "")
            if (string == 'false'){
                alert('You are banned from the CTF. Please contact ADMIN');
                window.location.replace("/logout.php");
                $(document).ajaxStop();
            }
            else if (string == 'old'){
                alert('This is old sessions you are logging out...');
                window.location.replace("/logout.php");
                $(document).ajaxStop();
            }
            else if (string == 'error'){
                alert('You are banned from the CTF. Please contact ADMIN');
                window.location.replace("/logout.php");
                $(document).ajaxStop();
            }              
        }
    });
}
window.onload = function() {
  yourAjaxCall();
}

</script>

</div>