<?php 

function clean($str)
{
    return htmlentities($str);
}

function redirect($location)
{
    header("location: {$location}");
    exit();
}

function set_message($message){
    if(!empty($message)){
        $_SESSION['message'] = $message;
    } else {
        $message = "";
    }
}

function display_message()
{
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function validate_user_registration() {

    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $first_name = clean($_POST['first_name']);
        $last_name = clean($_POST['last_name']);
        $username = clean($_POST['username']);
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        $confirm_password = clean($_POST['confirm_password']);

        if (strlen($first_name) < 2) {
            $errors[] = "Your First Name cannot be less then 2 characters";
        }
        if (strlen($last_name) < 2) {
            $errors[] = "Your Last Name cannot be less then 2 characters";
        }
        
        if (!preg_match("#[0-9]+#", $password) || strlen($password) < 6) {
            $errors[] = "Your Password cannot be less then 6 characters and should include at leaste one number!";
        }

        if ($password != $confirm_password) {
            $errors[] = "The password was not confirmed correctly";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div class="alert">' . $error . '</div>';
            }
        } else {
            $first_name = filter_var($first_name, FILTER_UNSAFE_RAW);
            $last_name = filter_var($last_name, FILTER_UNSAFE_RAW);
            $username = filter_var($username, FILTER_UNSAFE_RAW);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $password = filter_var($password, FILTER_UNSAFE_RAW);
            create_user($first_name, $last_name, $username, $email, $password);
        }
    }   

}

function create_user($first_name, $last_name, $username, $email, $password) {
     
    $first_name = escape($first_name);
    $last_name = escape($last_name);
    $username = escape($username);
    $email = escape($email);
    $password = escape($password);
    $password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users(first_name, last_name, username, email, password)";
    $sql .= "VALUES('$first_name', '$last_name', '$username', '$email', '$password')";
    
    confirm(query($sql));
    set_message("You have been successfully registrated! Please Log in!");
    //redirect('login.php');
    
}

function validate_user_login() {

    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = clean($_POST['username']);
        $password = clean($_POST['password']);

        if (empty($username)) {
            $errors[] = "Username field cannot be empty";
        }
        if (empty($password)) {
            $errors[] = "Password field cannot be empty";
        }
        if (empty($errors)) {
            if (user_login($username, $password)) {
                redirect('feed.php');
            } else {
                $errors[] = "Your email or password is incorrect. please try again";
            }
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div class="alert">' . $error . '</div>';
            }
        }
    }
}



function user_login($username, $password)
{
    $password = filter_var($password, FILTER_UNSAFE_RAW);
    $username = filter_var($username, FILTER_UNSAFE_RAW);

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        if (password_verify($password, $data['password'])) { //integrirana php funkcija koja ce provjeriti da li je password u plaintextu koji smo mi unijeli jednak sa hash passwordom iz baze
            $_SESSION['username'] = $username;
            return true;
        } else {
            return false;
            }
    } else {
        return false;
    }

    
}

