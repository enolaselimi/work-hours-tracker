<?php 
$title = "Sign In";
include('includes/header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        require('includes/loginFunctions.inc.php');

        list($check, $data) = check_login($dbc, $_POST['email'], $_POST['pass']);
        if($check){
            include('includes/header.html');
            $_SESSION['emp_id'] = $data['emp_id'];
            $_SESSION['role'] = $data['role'];
            redirect_user("dashboard.php?eid={$_SESSION['emp_id']}&r={$_SESSION['role']}");
        }else{
            $errors = $data;
        }
    }
include('includes\loginForm.inc.php');
}
else{
    header('location: dashboard.php');
}
?>