<?php 
$title = "Sign In";
include('includes/header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: login.php');
}else{
    header('location: dashboard.php');
}
include('includes/footer.html');
?>