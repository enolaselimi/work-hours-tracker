<?php
$title = "Dashboard";
include('includes/header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: index.php');
}else{
    require('includes/tracker_connect.php');
    if($_SESSION['role'] == 1){
        $month = date('F');

        $q = "SELECT CONCAT(b.fname,' ', b.lname) as employee, COUNT(a.late_arrival) as late
              FROM attendances AS a
                INNER JOIN 
                    users AS b on a.emp_id = b.emp_id
              WHERE a.late_arrival = '1' AND (MONTH(curr_date) = MONTH(CURRENT_DATE()))
              GROUP BY employee";
        $r = @mysqli_query($dbc, $q);
        echo '
        <div class="recap">
            <div class="page-header">
                <h4> <b>Summary for </b>'.$month.'
            </div>
            <div class="col-md-6">
                <h5 class="sukses"> Attendances <h5>
                <table class="table table-striped table-hover table-rensponsive">
                    <thead> 
                        <th>Employee</th>
                        <th>Total Hours Working </th>
                        <th>Late Arrivals</th>
                        <th>Early Departures</th>
                    </thead>
                    <tbody>
        ';
        $q2 = "SELECT CONCAT(b.fname,' ', b.lname) as employee, COUNT(a.early_leave) as early
               FROM attendances AS a
                    INNER JOIN 
                 users AS b on a.emp_id = b.emp_id
               WHERE (a.early_leave = '1') AND (MONTH(curr_date) = MONTH(CURRENT_DATE()))
               GROUP BY employee";
        $r2 = @mysqli_query($dbc, $q2);

        $q3 = "SELECT CONCAT(b.fname,' ', b.lname) as employee, SEC_TO_TIME(SUM(a.hr_worked)) AS hrw
               FROM attendances AS a 
                    INNER JOIN 
                users as b ON a.emp_id = b.emp_id
                WHERE (MONTH(curr_date) = MONTH(CURRENT_DATE()))
               GROUP BY employee";
        $r3 = @mysqli_query($dbc, $q3); 

        
        while(($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) && ($row2 = mysqli_fetch_array($r2, MYSQLI_ASSOC))  && ($row3 = mysqli_fetch_array($r3, MYSQLI_ASSOC))){
            echo "<tr> 
                    <td>{$row['employee']}</td>
                    <td>{$row3['hrw']}</td>
                    <td>{$row['late']}</td>
                    <td>{$row2['early']}</td>
                </tr>";
        }
        
        echo'
            </tbody>
            </table>
            </div>

            <div class="col-md-6">
                <h5 class="error"> Leaves </h5>
                <table class="table table-striped table-hover table-rensponsive">
                <thead> 
                    <th>Employee</th>
                    <th>Approved </th>
                    <th>Denied</th>
                    <th>Pending</th>
                </thead>
                <tbody>';

                $q4 = "SELECT CONCAT(b.fname,' ', b.lname) as employee,  COUNT(rejected) AS rejected          
                       FROM( SELECT emp_id, leave_id as rejected
                             FROM leaves 
                             WHERE (MONTH(from_date) = MONTH(CURRENT_DATE())) AND leave_status = 'rejected'
                            ) AS a 
                        RIGHT JOIN users AS b ON a.emp_id = b.emp_id
                       WHERE b.role = '0'
                       GROUP BY employee";
                $r4 = @mysqli_query($dbc, $q4);

                $q5 = "SELECT CONCAT(b.fname,' ', b.lname) as employee,  COUNT(approved_leaves)  AS approved        
                       FROM( SELECT emp_id, leave_id as approved_leaves
                             FROM leaves 
                             WHERE (MONTH(from_date) = MONTH(CURRENT_DATE())) AND leave_status = 'approved'
                            ) AS a 
                        RIGHT JOIN users AS b ON a.emp_id = b.emp_id
                       WHERE b.role = '0'
                       GROUP BY employee";
                $r5 = @mysqli_query($dbc, $q5);

                $q6 = "SELECT CONCAT(b.fname,' ', b.lname) as employee, COUNT(pending)  AS pending       
                       FROM( SELECT emp_id, leave_id as pending
                             FROM leaves 
                             WHERE (MONTH(from_date) = MONTH(CURRENT_DATE())) AND leave_status = 'pending'
                            ) AS a 
                        RIGHT JOIN users AS b ON a.emp_id = b.emp_id
                       WHERE b.role = '0'
                       GROUP BY employee";
                $r6 = @mysqli_query($dbc, $q6);
                while(($row4 = mysqli_fetch_array($r4, MYSQLI_ASSOC)) && ($row5 = mysqli_fetch_array($r5, MYSQLI_ASSOC))  && ($row6 = mysqli_fetch_array($r6, MYSQLI_ASSOC))){
                    echo "<tr> 
                        <td>{$row4['employee']}</td>
                        <td>{$row5['approved']}</td>
                        <td>{$row4['rejected']}</td>
                        <td>{$row6['pending']}</td>
                    </tr>";
                }
                echo'
                    </tbody>
                </table>
            </div>
        </div>
        ';
    

    }elseif($_SESSION['role'] == 0){
        $query = "SELECT  CONCAT(fname,' ', lname) as employee FROM users WHERE emp_id=\"{$_SESSION['emp_id']}\" ";
        $res = @mysqli_query($dbc,$query);
        if(mysqli_num_rows($res) > 0){
            $name = mysqli_fetch_row($res);
            $name = implode($name);
        }

        $query2 = "SELECT MONTHNAME(CURRENT_DATE())";
        $res2 = @mysqli_query($dbc,$query2);
        if(mysqli_num_rows($res2) > 0){
            $month = mysqli_fetch_row($res2);
            $month = implode($month);
        }

        $q = "SELECT COUNT(a.late_arrival) as late
              FROM attendances AS a
                INNER JOIN 
                    users AS b on a.emp_id = b.emp_id
              WHERE a.emp_id = \"{$_SESSION['emp_id']}\" AND (MONTH(curr_date) = MONTH(CURRENT_DATE())) AND (late_arrival = '1')
              ";
        $r = @mysqli_query($dbc, $q);
        echo '
        <div class="recap">
            <div class="page-header">
                <h4> <b> Summary for: </b>'.$month.' <br><br>
                <b> Employee: </b>'.$name.'<h4>
            </div>
            <div class="col-md-12">
                <h4 class="text-center"> Attendances </h4>
                <br>
                <br>
                <table class="table table-hover table-rensponsive">
                    <thead> 
                        <th>Total Working Hours</th>
                        <th>Late Arrivals</th>
                        <th>Early Departures</th>
                    </thead>
                    </tbody>
        ';

        $q2 = "SELECT COUNT(a.early_leave) as early
               FROM attendances AS a
                    INNER JOIN 
                 users AS b on a.emp_id = b.emp_id
               WHERE a.emp_id = \"{$_SESSION['emp_id']}\" AND (MONTH(curr_date) = MONTH(CURRENT_DATE()))
               ";
        $r2 = @mysqli_query($dbc, $q2);

        $q3 = "SELECT SEC_TO_TIME(SUM(a.hr_worked)) AS hrw
               FROM attendances AS a 
                    INNER JOIN 
                users as b ON a.emp_id = b.emp_id
               WHERE a.emp_id = \"{$_SESSION['emp_id']}\" AND (MONTH(curr_date) = MONTH(CURRENT_DATE()))
               ";
        $r3 = @mysqli_query($dbc, $q3);

        while(($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) && ($row2 = mysqli_fetch_array($r2, MYSQLI_ASSOC))  && ($row3 = mysqli_fetch_array($r3, MYSQLI_ASSOC))){
            echo "<tr> 
                    <td class='success'>{$row3['hrw']}</td>
                    <td class='danger'>{$row['late']}</td>
                    <td class='warning'>{$row2['early']}</td>
                </tr>";
        }
        echo'
            </tbody>
            </table>
            </div>
            <div class="col-md-12">
            <br>
            <br>
                <h4 class="text-center"> Leaves </h4>
                <table class="table table-hover table-rensponsive">
                    <thead> 
                        <th>Approved   </th>
                        <th>Rejected</th>
                        <th>Pending</th>
                    </thead>
                    </tbody>
        ';
        $q4 = "SELECT COUNT(leave_id) AS approved 
                FROM leaves 
                WHERE leave_status='approved' AND (MONTH(from_date) = MONTH(CURRENT_DATE())) AND emp_id = \"{$_SESSION['emp_id']}\"";
        $r4 = @mysqli_query($dbc,$q4);
        
        $q5 = "SELECT COUNT(leave_id) AS rejected
                FROM leaves 
                WHERE leave_status='denied' AND (MONTH(from_date) = MONTH(CURRENT_DATE())) AND emp_id = \"{$_SESSION['emp_id']}\"";
        $r5 = @mysqli_query($dbc,$q5);

        $q6 = "SELECT COUNT(leave_id) AS pending
                FROM leaves 
                WHERE leave_status='pending' AND (MONTH(from_date) = MONTH(CURRENT_DATE())) AND emp_id = \"{$_SESSION['emp_id']}\"";
        $r6 = @mysqli_query($dbc,$q6);

        while(($row4 = mysqli_fetch_array($r4, MYSQLI_ASSOC)) && ($row5 = mysqli_fetch_array($r5, MYSQLI_ASSOC))  && ($row6 = mysqli_fetch_array($r6, MYSQLI_ASSOC))){
            echo "<tr> 
                    <td class='success'>{$row4['approved']}</td>
                    <td class='danger'>{$row5['rejected']}</td>
                    <td class='warning'>{$row6['pending']}</td>
                </tr>";
        }
        echo'
            </div>
        </div>
        ';
    }
}

include('includes/footer.html');
?>