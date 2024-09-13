<?php 
$title = "Sign Up";
include('includes/header.html');
include('includes/tracker_connect.php');

if(isset($_SESSION['emp_id']) && $_SESSION['role'] == '0'){
    require('includes/loginFunctions.inc.php');
    redirect_user("dashboard.php");
}

if(!isset($_SESSION['emp_id']) && !isset($_SESSION['role'])){
    header("location: index.php");
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors = []; 
    $user_exists = FALSE;

    if (empty($_POST['fname'])) {
        $errors[] = 'First name cannot be empty.';
    } else {
        $fn = mysqli_real_escape_string($dbc, trim($_POST['fname']));
    }
    
    if (empty($_POST['lname'])) {
        $errors[] = 'Last name cannot be empty.';
    } else {
        $ln = mysqli_real_escape_string($dbc,trim($_POST['lname']));
    }
    
    if (empty($_POST['email'])) {
        $errors[] = 'Email address cannot be empty.';
    } else {
        $e = mysqli_real_escape_string($dbc,trim($_POST['email']));
        if(filter_var($e,FILTER_VALIDATE_EMAIL)){
            $qt = "SELECT emp_id FROM users WHERE email='$e'";
            $rt = @mysqli_query($dbc,$qt);
            $num = mysqli_num_rows($rt);
            if($num == 1){
                $user_exists = TRUE;
            }
        }else{
            $errors[] = "Invalid email";
        }
    }

    if(empty($_POST['pass'])){
        $errors[] = 'Password is required';
    }elseif($_POST['pass'] != $_POST['passConf']){
        $errors[] = 'Passwords do not match';
    }else{
        $p = mysqli_real_escape_string($dbc, trim($_POST['pass']));
    }

    if(isset($_POST['role']) && ($_POST['role']!=null)){
        $role = mysqli_real_escape_string($dbc,$_POST['role']);
    }else{
        $errors[] = 'Please select your role.';
    }

    if(empty($errors)){
        if(!$user_exists){
            $q = "INSERT INTO users (fname, lname, email, pass, role)
            VALUES('$fn', '$ln', '$e', SHA2('$p',512), '$role')";
            $r = @mysqli_query($dbc,$q);
            if(mysqli_affected_rows($dbc) == 1){
                echo '<div class="sukses">Sign up Complete! <br>';
                echo '</div>';
            }else{
                echo '<div class="error">The registration was not complete due to a server error <br> 
                Please try again</div>';
            }
        }else{
            echo '<div class="error">This email is already in use</div>';
        }
    }else{
        echo '<div class="error">'.$errors[0].'</div>';
    }
}
?>
<div class="starter-template">
<div class="page-header">
    <h3> Create Account </h3>
</div>
    <form action="" method="POST">
        <div class="controls col-md-6">
            <label class="form-label" for="fname">First Name</label>
            <input type="text" id="fname" name="fname" class="form-control form-control-lg" />
        </div>

        <div class="controls col-md-6">
            <label class="form-label" for="lname">Last Name</label>
            <input type="text" id="lname" name="lname" class="form-control form-control-lg" />
        </div>

        <div class="controls col-md-12">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control form-control-lg" />
        </div>

        <div class="controls col-md-12">
            <label class="form-label" for="pass">Password</label>
            <input type="password" id="pass" name="pass" class="form-control form-control-lg" />
        </div>

        <div class="controls col-md-12">
            <label class="form-label" for="passConf">Confirm password</label>
            <input type="password" id="passConf" name="passConf" class="form-control form-control-lg" />
        </div>

        <div class="controls col-md-12">
            <label class="form-label" for="role">Role</label>
            <select class="form-control form-control-lg" name="role" id="role">
                <option value="null" disabled selected>Select role</option>
                <option value="0">User</option>
                <option value="1">Admin</option>
            </select>
        </div>

        <div class="controls col-md-12">
            <br>
            <input type="submit" name="submit" value="Sign Up">
        </div>   
    </form>

<?php
include('includes/footer.html');
?>