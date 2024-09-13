<?php
$title = "Edit Profile";
include('includes\header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: index.php');
}else{
    if(isset($_GET['i']) && isset($_GET['r'])){
        $id = $_GET['i'];
        $role = $_GET['r'];
    }else{
        $id = $_SESSION['emp_id'];
        $role = $_SESSION['role'];
    }

    require('includes/tracker_connect.php');
    echo '
    <div class="box"> 
        <div class="page-header"
            <h4> <b> EDIT PROFILE </b> </h4>
        </div>
    ';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $errors = [];
        
        if(empty($_POST['first_name'])){
            $errors = "You forgot to enter first name.";
        }else{
            $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
        }
    
        if(empty($_POST['last_name'])){
            $errors = "You forgot to enter last name.";
        }else{
            $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
        }
    
        if(empty($_POST['email'])){
            $errors = "You forgot to enter email.";
        }else{
            $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
        }
    
        if(empty($errors)){
            $q = "SELECT emp_id FROM users WHERE email='$e' AND emp_id != '$id'";
            $r = @mysqli_query($dbc, $q);
            $num = mysqli_num_rows($r);
            if($num == 0){
                $q = "UPDATE users SET
                      fname='$fn', lname='$ln', email='$e'
                      WHERE emp_id=$id
                      LIMIT 1";
                $r = @mysqli_query($dbc, $q);
                if(mysqli_affected_rows($dbc) == 1){
                    echo '<div class="sukses">The user has been edited.</div>';
                }else{
                    echo '<div class="error">The user could not be edited due to a system error.<br>
                            We apologize for any inconvenience. </div>'; 
                }
            }else{
                echo '<div class="error"> This email is already in use. </div>';
            }
        }else{
            echo '<div class="error">'.$errors[0].'</div>';
        }
    }
    $q = "SELECT fname, lname, email, role 
          FROM users
          WHERE emp_id='$id'";
    $r = @mysqli_query($dbc, $q);
    if(mysqli_num_rows($r) == 1){
        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
        echo '<form action="edit.php" method="POST"> 
            <label> First Name: </label><br>
            <input type="text" class="form-control form-control-lg" name="first_name" maxlength="20" size="20" value="'.$row['fname'].'"><br><br>
            <label> Last Name: </label><br>
            <input type="text" class="form-control form-control-lg" name="last_name" maxlength="30" size="20" value="'.$row['lname'].'"><br><br>
            <label> Email: </label><br>
            <input type="email" class="form-control form-control-lg" name="email" maxlength="60" size="25" value="'.$row['email'].'">
            <br><br>
            <button class="btn-sm btn-primary" type="submit" name="submit" value="submit">Save</button>
            <a href="settings.php">Cancel</a>
            <input type="hidden" name="id" value="'.$id.'">
            </form> ';
    }else{
        echo '<p class="error"> This page has been accessed in error</p>';
    }

mysqli_close($dbc);
include('includes\footer.html'); 
}  
?>