<?php 
include('includes/header.html');
require('includes/tracker_connect.php');
if($_SESSION['role']==0){
    $query = "SELECT curr_date, clock_in, clock_out, hr_worked, overtime 
        FROM attendances 
        WHERE emp_id = {$_SESSION['emp_id']} AND (MONTH(curr_date) = MONTH(CURRENT_DATE()))";

    if(isset($_POST['query'])){
        $query .= "AND (curr_date LIKE '%{$_POST['query']}%' 
            OR clock_in LIKE '%{$_POST['query']}%'
            OR clock_out LIKE '%{$_POST['query']}%'
            OR hr_worked LIKE '%{$_POST['query']}%'
            OR overtime LIKE '%{$_POST['query']}%') ";
    }
}elseif($_SESSION['role']==1){
    $query = "SELECT CONCAT(b.fname,' ',b.lname) as emp, a.curr_date, a.clock_in, a.clock_out, a.hr_worked, a.overtime 
              FROM attendances AS a
                 INNER JOIN 
                users AS b USING(emp_id)
              WHERE (MONTH(curr_date) = MONTH(CURRENT_DATE()))";
    
    if(isset($_POST['query'])){
        $query .= "AND (curr_date LIKE '%{$_POST['query']}%' 
            OR clock_in LIKE '%{$_POST['query']}%'
            OR CONCAT(b.fname,' ',b.lname) LIKE '%{$_POST['query']}%'
            OR clock_out LIKE '%{$_POST['query']}%'
            OR hr_worked LIKE '%{$_POST['query']}%'
            OR overtime LIKE '%{$_POST['query']}%') ";
    }
}


if(!empty($_POST['from_date']) && !empty($_POST['to_date'])){
    $query .= 'AND curr_date BETWEEN \''.$_POST['from_date'].'\' AND \''.$_POST['to_date'].'\' ';
}



$result = mysqli_query($dbc, $query);
if($_SESSION['role']==0){
    echo '
    <table id="data_table" width="100%" class="table table-striped table-bordered table-hover table-rensponsive">
            <thead>
                <tr class="table-header"> 
                    <th>Date</th>
                    <th>Clock in</th>
                    <th>Clock out</th>
                    <th>Total Hours</th>
                    <th>Overtime Hours</th>
                </tr> 
            </thead>
            <tbody id="export_table">
    ';

    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            echo '
            <tr>
            <td align="left">'.$row['curr_date'].'</td>
            <td align="left">'.$row['clock_in'].'</td>
            <td align="left">'.$row['clock_out'].'</td>
            <td align="left">'.$row['hr_worked'].'</td>
            <td align="left">'.$row['overtime'].'</td>
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
                    <th>Date</th>
                    <th>Clock in</th>
                    <th>Clock out</th>
                    <th>Total Hours</th>
                    <th>Overtime Hours</th>
                </tr> 
            </thead>
            <tbody id="export_table">
    ';
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            echo '
            <tr>
            <td align="left">'.$row['emp'].'</td>
            <td align="left">'.$row['curr_date'].'</td>
            <td align="left">'.$row['clock_in'].'</td>
            <td align="left">'.$row['clock_out'].'</td>
            <td align="left">'.$row['hr_worked'].'</td>
            <td align="left">'.$row['overtime'].'</td>
            </tr>
            ';
        }
        echo '</tbody></table>';
    }
}