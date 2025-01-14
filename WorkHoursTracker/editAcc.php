<?php
$title = "Manage Accounts";
include('includes/header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: index.php');
}else{
    require('includes/tracker_connect.php');
    echo '
    <div class="box"> 
        <div class="col-md-12">
            <a href="register.php" class="btn-sm btn-success" role="button" style="float:right; text-decoration:none"> Add User </a>
        </div>
        <div class="page-header"
            <h4> <b> MANAGE ACCOUNTS </b> </h4>
        </div>
    ';

    $q = "SELECT * FROM users WHERE role='0'"; 
    $r = @mysqli_query($dbc, $q);
    echo'
    <div id="order_table">  
    <table id="data_table" width="100%" class="table table-striped table-bordered table-hover table-rensponsive">
        <thead>
            <tr class="table-header"> 
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr> 
        </thead>
        <tbody id="export_table">';
    while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
        echo '
        <tr>
        <td align="left">'.$row['fname'].'</td>
        <td align="left">'.$row['lname'].'</td>
        <td align="left">'.$row['email'].'</td>
        <td align="left">User</td>
        <td align="center"><a href="ManEdit.php?i=' . $row['emp_id'].'&r='.$row['role'].'">Edit</a></td>
        <td align="center"><a href="ManDelete.php?i=' . $row['emp_id'].'">Delete</a></td>
        </tr>
        ';
    }
    echo'
            </tbody>
        </table>
    </div>
    ';
}
include('includes/footer.html');
?>