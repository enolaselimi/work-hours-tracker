<?php 
include('includes/header.html');
require('includes/tracker_connect.php');
if($_SESSION['role']==0){
    $query = "SELECT b.type, a.from_date, a.to_date, a.return_date, a.comments, a.leave_status 
              FROM leaves AS a 
                  INNER JOIN 
                leavetypes AS b ON a.type = b.type_id
              WHERE emp_id = {$_SESSION['emp_id']} ";

    if(isset($_POST['query'])){
        $query .= "AND (b.type LIKE '%{$_POST['query']}%' 
            OR a.from_date LIKE '%{$_POST['query']}%'
            OR a.to_date LIKE '%{$_POST['query']}%'
            OR a.return_date LIKE '%{$_POST['query']}%'
            OR a.comments LIKE '%{$_POST['query']}%'
            OR a.leave_status LIKE '%{$_POST['query']}%') ";
    }

    if(!empty($_POST['from_date']) && !empty($_POST['to_date'])){
        $query .= 'AND a.from_date BETWEEN \''.$_POST['from_date'].'\' AND \''.$_POST['to_date'].'\' ';
    }


}elseif($_SESSION['role']==1){
    $query = "SELECT CONCAT(c.fname,' ',c.lname) AS employee, a.type, b.from_date, b.to_date, b.return_date, b.comments, b.leave_status 
              FROM leavetypes AS a 
                INNER JOIN 
              leaves AS b ON a.type_id = b.type
                INNER JOIN 
              users AS c ON b.emp_id = c.emp_id ";
    $flag = 0;

    if(isset($_POST['query'])){
        $flag = 1;
        $query .= "WHERE (CONCAT(c.fname,' ',c.lname) LIKE '%{$_POST['query']}%' 
            OR a.type LIKE '%{$_POST['query']}%'
            OR b.from_date LIKE '%{$_POST['query']}%'
            OR b.to_date LIKE '%{$_POST['query']}%'
            OR b.return_date LIKE '%{$_POST['query']}%'
            OR b.comments LIKE '%{$_POST['query']}%'
            OR b.leave_status LIKE '%{$_POST['query']}%') ";
    }

    if(!empty($_POST['from_date']) && !empty($_POST['to_date']) && ($flag = 1)){
        $query .= 'AND b.from_date BETWEEN \''.$_POST['from_date'].'\' AND \''.$_POST['to_date'].'\' ';
    }
    if(!empty($_POST['from_date']) && !empty($_POST['to_date']) && ($flag = 0)){
        $query .= 'WHERE b.from_date BETWEEN \''.$_POST['from_date'].'\' AND \''.$_POST['to_date'].'\' ';
    }
}

$result = mysqli_query($dbc, $query);
if($_SESSION['role']==0){
    echo '
    <table id="data_table" width="100%" class="table table-striped table-bordered table-hover table-rensponsive">
            <thead>
                <tr class="table-header"> 
                    <th>Leave Type</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Return Date</th>
                    <th>Comments</th>
                    <th>Status</th>
                </tr> 
            </thead>
            <tbody id="export_table">
    ';

    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            echo '
            <tr>
            <td align="left">'.$row['type'].'</td>
            <td align="left">'.$row['from_date'].'</td>
            <td align="left">'.$row['to_date'].'</td>
            <td align="left">'.$row['return_date'].'</td>
            <td align="left">'.$row['comments'].'</td>
            <td align="left">'.$row['leave_status'].'</td>
            </tr>
            ';
        }
        echo '</tbody>';
    }
}elseif($_SESSION['role']==1){
    echo '
    <table id="data_table" width="100%" class="table table-striped table-bordered table-hover table-rensponsive">
            <thead>
                <tr class="table-header">
                    <th>Employee</th> 
                    <th>Leave Type</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Return Date</th>
                    <th>Comments</th>
                    <th>Status</th>
                </tr> 
            </thead>
            <tbody id="export_table">
    ';
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            echo '
            <tr>
            <td align="left">'.$row['employee'].'</td>
            <td align="left">'.$row['type'].'</td>
            <td align="left">'.$row['from_date'].'</td>
            <td align="left">'.$row['to_date'].'</td>
            <td align="left">'.$row['return_date'].'</td>
            <td align="left">'.$row['comments'].'</td>
            <td align="left">'.$row['leave_status'].'</td>
            </tr>
            ';
        }
        echo '</tbody></table>';
    }
}