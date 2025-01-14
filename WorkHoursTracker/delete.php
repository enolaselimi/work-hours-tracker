<?php
$title = "Delete Account";
include('includes\header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: index.php');
}else{
    require('includes/tracker_connect.php');
    echo '
    <div class="box"> 
        <div class="page-header"
            <h4> <b> DELETE YOUR ACCOUNT </b> </h4>
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

        if($_POST['conf'] == 'no'){
            $errors[] = 'Account NOT deleted!';
        }

        if(empty($errors)){
            $q = "SELECT * FROM users WHERE (email='$e' AND pass=SHA2('$p', 512))";
            $r = @mysqli_query($dbc, $q);
            if(mysqli_num_rows($r) == 1){
                $q = "DELETE FROM users WHERE emp_id = '{$_SESSION['emp_id']}'";
                $r = @mysqli_query($dbc, $q);
                if(mysqli_affected_rows($dbc) == 1){
                    echo '<div class="sukses">Your account has been deleted.</div>';
                    include('logout.php');
                }else{
                    echo '<div class="error">The user could not be deleted due to a system error.<br>
                    We apologize for any inconvenience. </div>';
                }
            }else{
                echo '<div class="error">The email address and password do not match those on file.</div>';
            }
        }else{
            echo '<div class="error">'.$errors[0].'</div>';
        }
    }else{
        echo '
        <div id="boxDel"> 
        <form action="delete.php" method="POST" class="form-horizontal form" > 
            <div class="form-group">';
?>
            <label> Email address: </label><br>
            <input class="form-control form-control-lg" type="email" name="email" maxlength="60" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"><br><br>

            <label> Current password: </label><br>
            <input class="form-control form-control-lg" type="password" name="pass" maxlength="50" value="<?php if(isset($_POST['pass'])) echo $_POST['pass']; ?>"><br><br>
<?php
            echo'
                <label class="col-sm-6 control-label">Are you sure you want to delete your account?</label>
                <div class="col-sm-5 radios">
                    <div class="radio radio-danger">
                        <input type="radio" name="conf" id="Radios1" value="yes">
                        <label>
                            Yes
                        </label>
                    </div>
                    <div class="radio radio-danger">
                        <input type="radio" name="conf" id="Radios2" value="no" checked>
                        <label>
                            No
                        </label>
                    </div>  
                </div>                     
            </div>
            <div class="del">
                <button value="Delete" name="submit" type="submit" class="btn-sm btn-primary">Delete</button>
                <a href="settings.php">Cancel</a>
            </div>
        </form>
        </div>
        ';
    }
}
include('includes/footer.html');
?>