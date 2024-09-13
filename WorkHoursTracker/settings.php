<?php 
$title = "Settings";
include('includes/header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: index.php');
}else{
    echo '
    <div class="box"> 
        <div class="page-header"
            <h4> <b> ACTIONS </b> </h4>
        </div>
    ';
    if($_SESSION['role'] == '0'){
        echo '<div class="list-group col-md-12">
            <a href="edit.php" class="list-group-item list-group-item-success">Edit Profile</a>
            <a href="changePass.php" class="list-group-item list-group-item-success">Change Password</a>
            <a href="delete.php" class="list-group-item list-group-item-danger">Delete Account</a>
        </div>
    </div>';
    }elseif($_SESSION['role'] == '1'){
        echo '<div class="list-group col-md-12">
            <a href="addLeaveType.php" class="list-group-item list-group-item-success">Add Leave Type</a>
            <a href="editAcc.php" class="list-group-item list-group-item-success">Manage Accounts</a>
            <a href="edit.php" class="list-group-item list-group-item-success">Edit Your Profile</a>
            <a href="changePass.php" class="list-group-item list-group-item-success">Change Password</a>
            <a href="delete.php" class="list-group-item list-group-item-danger">Delete Your Account</a>
        </div>
    </div>';
    }
}
include('includes/footer.html');
?>