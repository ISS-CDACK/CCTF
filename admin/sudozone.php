<?php
include '../admin_session.php';
include '../config.php';

$keypage = "keypage";
$permission = "permission";
$setting = "setting";

$current_page = "";


if (isset($_GET["p"]) && $_GET["p"] == $keypage) {
    $current_page = $keypage;
} else if (isset($_GET["p"]) && $_GET["p"] == $permission){
    $current_page = $permission;
} else if (isset($_GET["p"]) && $_GET["p"] == $setting){
    $current_page = $setting;
} else {
    header('Location: /admin.php?p=dashboard');
    die();
}

?>

<!DOCTYPE html>
<html>

<?php include '../includes/header.php' ?>
<body>
    <div class="admin-nav">
        <div class="nav">
            <ul>
                <?php
                    if($current_page == $keypage){
                        echo "<li><a href='?p=keypage' class='active'>Key Generate</a></li>";
                        echo "<li><a href='?p=permission' >Permission</a></li>";
                        if (!$ldap_connection){
                            echo "<li><a href='?p=settings' >Settings</a></li>";
                        }
                    } else if ($current_page == $permission) {
                        echo "<li><a href='?p=keypage'>Key Generate</a></li>";
                        echo "<li><a href='?p=permission' class='active' >Permission</a></li>";
                        if (!$ldap_connection){
                            echo "<li><a href='?p=settings' >Settings</a></li>";
                        }
                    }
                    else if ($current_page == $setting) {
                        echo "<li><a href='?p=keypage'>Key Generate</a></li>";
                        echo "<li><a href='?p=permission'>Permission</a></li>";
                        echo "<li><a href='?p=settings' >Settings</a></li>";
                    }
                    ?>
                    <li><a href='/admin.php'>Home</a></li>
            <!-- <li><a href='?p=leaderboard' >Gereneret Key For User</a></li> -->
            </ul>
            <a href="../logout.php" class="logout">Logout</a>
        </div>
    </div>
    <?php
        if($current_page == $keypage){
            include 'keytable.php';
        } else if ($current_page == $permission) {
            include 'permissions.php';
        }else if ($current_page == $setting && !$ldap_connection) {
           include 'settings.php';
        }
    ?>

<script src="/js/jquery-3.2.1.min.js"></script>
<script>
setInterval("yourAjaxCall()",10000);
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
</body>
</html>