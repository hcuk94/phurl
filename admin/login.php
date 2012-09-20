<?php
session_start();
require_once("../config.php");
require_once("../functions.php");

if (count($_POST) > 0) {
    if ($_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD) {
        $_SESSION['admin'] = 1;

        session_write_close();
        header("Location: index.php", true, 301);
        exit();
    }
    else {
        $_ERROR[] = "Wrong username or password.";

        print_errors();
    }
}
require_once("header.php");
print_errors();
?>
<h2>Administration Login</h2>
<form method="post" action="login.php">
<table id="admin_login">
<tr>
<td><strong>Username:</strong></td>
<td><input type="text" name="username" size="30" value="" /></td>
</tr>
<tr>
<td><strong>Password:</strong></td>
<td><input type="password" name="password" size="30" value="" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" value="Login" /></td>
</tr>
</table>
</form>

<?php
require_once("footer.php");
?>