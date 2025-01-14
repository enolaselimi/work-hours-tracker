<?php
$title = "Delete Account";
include('includes/header.html');
if(!isset($_GET['i']) || !isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: index.php');
}else{
    require('includes/tracker_connect.php');
    echo '
    <div class="box"> 
        <div class="page-header"
            <h4> <b> EDIT PROFILE </b> </h4>
        </div>
    ';
    $id = $_GET['i'];
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
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
            if($row['role'] == 1){
                $q = "DELETE FROM users WHERE emp_id = '$id'";
                $r = @mysqli_query($dbc, $q);
                if(mysqli_affected_rows($dbc) == 1){
                    echo '<div class="sukses">Account has been deleted.</div>';
                }else{
                    echo '<div class="error">The user could not be deleted due to a system error.<br>
                    We apologize for any inconvenience. </div>';
                }
            }else{
                echo '<div class="error">You do not have permission to delete accounts.</div>';
            }
        }else{
            echo '<div class="error">'.$errors[0].'</div>';
        }
    }else{
        echo '
        <div id="boxDel"> 
        <form action="ManDelete.php?i='.$_GET['i'].'" method="POST" class="form-horizontal form" > 
            <div class="form-group">
            <label> Admin email address: </label><br>
            <input class="form-control form-control-lg" type="email" name="email" maxlength="60" value=""><br><br>

            <label> Admin password: </label><br>
            <input class="form-control form-control-lg" type="password" name="pass" maxlength="50" value=""><br><br>
                <label class="col-sm-6 control-label">Are you sure you want to delete this account?</label>
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