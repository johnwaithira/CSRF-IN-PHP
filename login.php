<?php 

require_once "./app/CSRF.php";
?>
<h2>Login Form With CSRF token</h2>
<form action="./app/handleform.php" method="post">
    <input type="text" name="username" placeholder="Username">
    <br>
    <?php CSRF::create_token();?>
    <br>
    <input type="password" name="password" placeholder="Password">
    <br>
    <input type="submit" name="login" value="Login">
</form>

