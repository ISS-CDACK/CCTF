<form action="pass_reset.php" method="POST">
    <input type="email" name="email" placeholder="Email Address" required readonly
     onfocus="this.removeAttribute('readonly');" autocomplete="off"/>
    <input type="text" name="key" placeholder="Enter Secret Key" required minlength="4" maxlength="22" size="22" readonly
     onfocus="this.removeAttribute('readonly');" autocomplete="off"/>
    <input type="submit" name="reset-submit" value="Submit">
</form>