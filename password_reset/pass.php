<form id='pass-reset' action="set_pass.php" method="POST">
    <input type="password" id="password" name="password" placeholder="Password" autocomplete="off" required minlength="4" maxlength="12" size="12" readonly
     onfocus="this.removeAttribute('readonly');"/>
    <input type="password" id="repassword" name="repassword" placeholder="Retype Password" autocomplete="off" required minlength="4" maxlength="12" size="12" readonly
     onfocus="this.removeAttribute('readonly');"/>
    <input type="submit" name="pass-reset" value="Set Password">
</form>

<script>
        let form = document.getElementById('pass-reset');
        form.addEventListener('submit', (e) => {

            e.preventDefault();

            let pswd1 = document.getElementById('password');
            let pswd2 = document.getElementById('repassword');
            let p1 = pswd1.value.trim();
            let p2 = pswd2.value.trim();
            if (p1 !== p2 && p1.length >= 3) {
                myFunction("Passwords doesn't match");
                return false;
            } else {
                form.submit();
                return true;
            }
        });
    </script>