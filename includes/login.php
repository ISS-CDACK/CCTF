<form action="<?php echo ($ldap_connection) ? "login_ldap.php" : "login.php"; ?>" method="POST">
    <input type="email" name="email" placeholder="Email Address" required readonly
     onfocus="this.removeAttribute('readonly');" autocomplete="off"/>
    <input type="password" name="password" placeholder="Password" required minlength="4" maxlength="40" size="12" readonly
     onfocus="this.removeAttribute('readonly');" autocomplete="off"/>
    <input type="submit" name="login-submit" value="Sign In" style="cursor: pointer;">
</form>
<?php if(!$ldap_connection){
    echo '<p><a href="/forgot_pass.php?p=read">Forget Password</a></p>';
}