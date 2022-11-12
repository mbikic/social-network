<?php include "inc/header.php" ?>

<div>
<?php 
display_message(); 
validate_user_login(); 
?>
</div>

<form method="POST">
        <input type="text" name="username" placeholder="Username" value="" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login-submit" value="Log In">
</form>

<?php include "inc/footer.php"; ?>