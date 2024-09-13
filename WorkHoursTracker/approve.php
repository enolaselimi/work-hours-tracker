<?php

include('includes/header.html');
require('includes/tracker_connect.php');

if(!isset($_GET['id'])){
    header('location: index.php');
}else{
    $id = $_GET['id']; 
    $q = "UPDATE leaves SET leave_status='approved' WHERE leave_id='$id' ";
    $r = @mysqli_query($dbc, $q);
    if(mysqli_affected_rows($dbc) == 1){
        echo '<div class="sukses"> Leave Approved! </div>'; 
    }else{
        echo '<div class="error"> Error occurred! </div>';
    }
}
?>