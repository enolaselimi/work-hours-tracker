<?php 
include('tracker_connect.php');
function redirect_user($page = "index.php"){
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    $url = rtrim($url, '/\\');
    $url .= '/' . $page;
    header("Location:$url");
    exit();
}

function check_login($dbc, $email, $pass){
    $errors = []; 
    if(empty($email)){
        $errors[] = "Please enter your email";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Invalid email";
    }else {
        $e = mysqli_real_escape_string($dbc, trim($email));
    }

    if(empty($pass)){
        $errors[] = "Please enter your password";
    }else{
        $p = mysqli_real_escape_string($dbc, trim($pass));
    }

    if(empty($errors)){
        $q = "SELECT emp_id, role
              FROM users
              WHERE email='$e' AND pass=SHA2('$p',512)";
        $r = @mysqli_query($dbc, $q); 
        if(mysqli_num_rows($r) == 1){
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
            return [true, $row];
        }else{
            $errors[] = 'The email address and password entered do not match.';
        }
    }
    return [false, $errors];
}