<form action="login.php" method="POST">
    <input type="email" name="email" placeholder="Email Address" required readonly
     onfocus="this.removeAttribute('readonly');" autocomplete="off"/>
    <input type="password" name="password" placeholder="Password" required minlength="4" maxlength="12" size="12" readonly
     onfocus="this.removeAttribute('readonly');" autocomplete="off"/>
    <input type="submit" name="login-submit" value="Sign In">
</form>
<p><a href="/forgot_pass.php?p=read">Forget Password</a></p>