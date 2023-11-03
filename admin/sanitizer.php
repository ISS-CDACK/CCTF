<?php
function sanitizeInput($val) {
    include '../config.php';
    $sprey1 = mysqli_real_escape_string($conn,$val);
    $sprey2 = filter_var ($sprey1, FILTER_SANITIZE_STRING);
    $sprey3 = strip_tags($sprey2);
    $sprey4 = htmlspecialchars($sprey3);
    $sprey5 = trim($sprey4," ");
    return $sprey5;
}
?>