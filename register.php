<?php include('inc/header.php'); ?>

    <div>
    <?php 
    validate_user_registration(); //pozivamo funkciju za validaciju korisnika koja automatski poziva fju za kreiranje korisnika iz filea functions.php
    display_message();
    ?>
    </div>

    <form method="POST">
        <input type="text" name="first_name" placeholder="First Name" value="" required>
        <input type="text" name="last_name" placeholder="Last Name" value="" required>
        <input type="text" name="username" placeholder="Username" value="" required>
        <input type="email" name="email" placeholder="Email Address" value="" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="submit" name="register-submit" value="Register Now">
    </form>

<?php include('inc/footer.php'); ?>