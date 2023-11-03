<form id='create-account' name="create-account" action="login.php" method="POST">
    <input type="text" id="name" name="name" placeholder="Full Name" autocomplete="off" required readonly
     onfocus="this.removeAttribute('readonly');"/>
    <input type="email" id="email" name="email" placeholder="Email Address" autocomplete="off" required readonly
     onfocus="this.removeAttribute('readonly');"/>
    <input type="password" id="password" name="password" placeholder="Password" autocomplete="off" required minlength="4" maxlength="12" size="12" readonly
     onfocus="this.removeAttribute('readonly');"/>
    <input type="password" id="repassword" name="repassword" placeholder="Retype Password" autocomplete="off" required minlength="4" maxlength="12" size="12" readonly
     onfocus="this.removeAttribute('readonly');"/>
     <input type="hidden" name="create-account" value="Sign Up" />
     <input type="submit" name="create-account" value="Sign Up"/>
</form>

<script>
        let form = document.getElementById('create-account');
        form.addEventListener('submit', (e) => {

            e.preventDefault();

            let pswd1 = document.getElementById('password');
            let pswd2 = document.getElementById('repassword');
            let nam = document.getElementById('name');
            let mail = document.getElementById('email');

            let p1 = pswd1.value.trim();
            let p2 = pswd2.value.trim();
            let p3 = pswd2.value.trim();
            let p4 = pswd2.value.trim();
            if (p3.length >= 2){ 
                if (p4.length >= 4){ 
                    if (p1 !== p2) {
                        // myToast.showError("Passwords doesn't match", null);
                        // console.log('error');
                        myFunction("Passwords doesn't match");
                        return false;
                    } else {
                        // console.log('suss');
                        // console.log(pswd1);
                        form.submit();
                        return true;
                    }
                }
                else{
                    return false;
                }
            }
            else{
                    return false;
                }
        });
    </script>