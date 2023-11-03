<link rel="stylesheet" href="../css/style3.css">

<style>
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}
input:disabled + .slider {
  background-color: #808080;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>

<script>
    function myFunMakeFalse(x) {
        window.location.href = "./controllers/update_user.php?id="+x+"&state=false";
    }
    function myFunMakeTrue(x) {
        window.location.href = "./controllers/update_user.php?id="+x+"&state=true";
    }
</script>

<body>
    <div class="admin-dashboard" style='margin:100px 0 0 0'>
<div class="container">
    <div class="w3-show-inline-block">
        <a href="./controllers/update_user.php?id=all&state=true" style="text-decoration: none"> <button class="w3-btn w3-light-green">Active All</button> </a>
        <a href="./controllers/update_user.php?id=all&state=false" style="text-decoration: none"> <button class="w3-btn w3-red">Disable All</button> </a>
        <!-- <button onclick='GetSelected()' class="w3-btn w3-light-green">Submit</button> -->
            <!-- <button class="w3-btn w3-border">Button</button> -->
    </div> 

    <div class="toolbar">
        <h3>Account Status</h3>
        </div>
        <table id="Table1">
            <tr class="head">
                <!-- <th>Verfied</th> -->
                <th>Username</th>
                <th>Email</th>
                <th>Switch</th>
                <th>Status</th>
            </tr>
                <?php
                $sql = "SELECT `id`, `name`, `email`, `role`, `status` FROM `users`;";
                if (!$conn) {
                    die('Could not connect: ' . mysqli_error());
                }

                $result = mysqli_query($conn, $sql);

                if (!$result) {
                    die('Could not get data: ' . $sql . mysqli_error());
                }
                $count = mysqli_num_rows($result);
                if ($count > 0) {
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        echo '<tr class="content" id="user_9"'.$row["id"].'>';
                        if ($row["status"] == 'true'){
                            // echo '<td><input type="checkbox" checked=checked/></td>';
                            // echo "<td>".$row["id"]."</td>";
                            if ($row["role"] == 'admin'){ 
                                echo "<td>".$row["name"]." <small style='background-color:#7FFFD4;'>[ADMIN]</small></td>";
                                echo "<td id='mail'>".$row["email"]."</td>";
                                echo'
                                <td>
                                <label class="switch">
                                <input type="checkbox" checked=checked disabled="disabled" id="'.$row["id"].'" onclick="myFunMakeFalse(this.id)">
                                <span class="slider round"></span>
                                </label>
                                </td>';
                            }
                            else{
                                echo "<td>".$row["name"]."</td>";
                                echo "<td id='mail'>".$row["email"]."</td>";
                                echo'
                                <td>
                                <label class="switch">
                                <input  type="checkbox" checked=checked id="'.$row["id"].'" onclick="myFunMakeFalse(this.id)">
                                <span class="slider round"></span>
                                </label>
                                </td>';
                            }
    
                            // echo '<td> <a href="./controllers/update_user.php?id='.$row["id"].'&state=false"><button class="w3-btn w3-light-green">Deactive</button></a></td>' ;
                            echo "<td>Active</td>";
                        }
                        elseif ($row["status"] == 'false'){
                            // echo '<td><input type="checkbox"/></td>';
                            // echo "<td>".$row["id"]."</td>";
                            if ($row["role"] == 'admin'){ 
                                echo "<td>".$row["name"]." <small style='background-color:#7FFFD4;'>[ADMIN]</small></td>";
                                echo "<td id='mail'>".$row["email"]."</td>";
                                echo'
                                <td>
                                <label class="switch">
                                <input type="checkbox" id="'.$row["id"].'" onclick="myFunMakeTrue(this.id)">
                                <span class="slider round"></span>
                                </label>
                                </td>';
                            }
                            else{
                                echo "<td>".$row["name"]."</td>";
                                echo "<td id='mail'>".$row["email"]."</td>";
                                echo'
                                <td>
                                <label class="switch">
                                <input type="checkbox" id="'.$row["id"].'" onclick="myFunMakeTrue(this.id)">
                                <span class="slider round"></span>
                                </label>
                                </td>';
                            }
                            // echo '<td> <a href="./controllers/update_user.php?id='.$row["id"].'&state=true"><button class="w3-btn w3-red">&nbsp; Active &nbsp;</button></a></td>' ;
                            echo "<td>Review</td>";
                        }                           
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
  font-size: 13.8px;
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
    <?php
    if (isset($_GET['i'])){
        echo '<script type="text/JavaScript"> 
        document.getElementById("user_'.$_GET['i'].'").scrollIntoView();
        </script>';
         // document.getElementById("elementID").scrollIntoView();
    }
    // ?>  
     
    </body>