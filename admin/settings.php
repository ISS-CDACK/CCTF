<div class="admin-leaderboard" style='margin:84px 0 0 0'>
    <div class="container">
    <div class="settings">
    
        <h3>Settings</h3>
    <form action="controllers/edit_profile.php" method="POST">
        <label>Full Name</label>
        <input type="text" name="username" value="<?php echo $login_username ?>" required minlength="4" required readonly
     onfocus="this.removeAttribute('readonly');" autocomplete="off">
        <input type="submit" name="change-name" value="change">
    </form>
    <form id="form-password" action="controllers/edit_profile.php" method="POST">
        <label>Password</label>
        <input type="password" name="old-password" placeholder="Old Password" required size="8" readonly onfocus="this.removeAttribute('readonly');" autocomplete="off">
        <input type="password" id="pswd1" name="new-password" placeholder="New Password" required minlength="4"
       maxlength="8" size="8" readonly onfocus="this.removeAttribute('readonly');" autocomplete="off">
        <input type="password" id="pswd2" placeholder="Retype new Password" required minlength="4"
       maxlength="8" size="8" readonly onfocus="this.removeAttribute('readonly');" autocomplete="off">
        <input type="submit" name="change-password" value="change">
    </form>

    <script>
        let form = document.getElementById('form-password');
        form.addEventListener('submit', (e) => {

            e.preventDefault();

            let pswd1 = document.getElementById('pswd1');
            let pswd2 = document.getElementById('pswd2');
            let p1 = pswd1.value.trim();
            let p2 = pswd2.value.trim();
            if (p1 !== p2) {
                myToast.showError("Passwords doesn't match", null);
                return false;
            } else {
                // console.log(pswd1);
                form.submit();
                return true;
            }
        });
    </script>
    
</div>
    </div>

</div>
