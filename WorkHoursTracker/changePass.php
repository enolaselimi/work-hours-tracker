<?php
$title = "Change Password";
include('includes/header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: index.php');
}else{
    require('includes/tracker_connect.php');
    echo '
    <div class="box"> 
        <div class="page-header"
            <h4> <b> CHANGE YOUR PASSWORD </b> </h4>
        </div>
    ';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $errors = [];

        if (empty($_POST['email'])) {
            $errors[] = 'Please enter your email address.';
        }else {
            $e = mysqli_real_escape_string($dbc,trim($_POST['email']));
        }

        if(empty($_POST['pass'])){
            $errors[] = 'Please enter your current password.';
        }else {
            $p = mysqli_real_escape_string($dbc,trim($_POST['pass']));
        }

        if (!empty($_POST['pass1'])) {
            if ($_POST['pass1'] != $_POST['pass2']) {
                $errors[] = 'Your password did not match the confirmed password.';
            } else {
                $np = mysqli_real_escape_string($dbc,trim($_POST['pass1']));
            }
        } else {
            $errors[] = 'Please enter a new password.';
        }

        if(empty($errors)){
            $q = "SELECT emp_id FROM users WHERE (email='$e' AND pass=SHA2('$p', 512))";
            $r = @mysqli_query($dbc, $q);
            $num = mysqli_num_rows($r);
            if($num == 1){
                $row = mysqli_fetch_array($r, MYSQLI_NUM);
                $q = "UPDATE users SET pass=SHA2('$np',512) WHERE emp_id=$row[0]";
                $r = @mysqli_query($dbc, $q);
                if(mysqli_affected_rows($dbc) == 1){
                    echo '<div class="sukses">Your password has been changed.</div>';
                }else{
                    echo '<div class="error">The user could not be edited due to a system error.<br>
                    We apologize for any inconvenience. </div>';
                }
                mysqli_close($dbc);
                include ('includes\footer.html');
                exit();
            }else {
                echo '<div class="error">The email address and password do not match those on file.</div>';
            }
        }else{
            echo '<div class="error">'.$errors[0].'</div>';
        }
        mysqli_close($dbc);
    }
}
?>

<form action="changePass.php" method="POST"> 
    <label> Email address: </label><br>
    <input class="form-control form-control-lg" type="email" name="email" maxlength="60" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"><br><br>

    <label> Current password: </label><br>
    <input class="form-control form-control-lg" type="password" name="pass" maxlength="50" value="<?php if(isset($_POST['pass'])) echo $_POST['pass']; ?>"><br><br>

    <label> New password: </label><br>
    <input class="form-control form-control-lg" type="password" name="pass1" maxlength="50" value="<?php if(isset($_POST['pass1'])) echo $_POST['pass1']; ?>"><br><br>

    <label> Confirm new password: </label><br>
    <input class="form-control form-control-lg" type="password" name="pass2" maxlength="50" value="<?php if(isset($_POST['pass2'])) echo $_POST['pass2']; ?>"><br><br>

    <button class="btn-sm btn-primary" type="submit" name="submit" value="Save">Save</button>  <a href="settings.php">Cancel</a>
    <br><br>
</form>
<?php include ('includes\footer.html');?>
