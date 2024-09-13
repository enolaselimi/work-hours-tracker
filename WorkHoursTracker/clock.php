<?php
$title = "Clock In/Out";
include('includes/header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: index.php');
}
require_once('includes/tracker_connect.php');
$flag = 0;
$q = "SELECT * FROM attendances WHERE curr_date=CURRENT_DATE() AND emp_id={$_SESSION['emp_id']}";
$r = @mysqli_query($dbc,$q);
if(mysqli_num_rows($r) == 1){
    $flag = 1;
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && $flag==0){
    $cin = strtotime($_POST['cin']);
    $cout = strtotime($_POST['cout']);

    $late_arrival = 0;
    $early_dep = 0;
    $overtime = 0;
    $clock_in = date('H:i:s', $cin);
    $clock_out = date('H:i:s', $cout);
    $hr_worked = $cout - $cin;
    $e = mktime(18,00,00);
    $end = date('H:i:s',$e);
    $s = mktime(9,15,00);
    $start = date('H:i:s',$s);
    
    if($clock_out > $end){
        $overtime = $cout - strtotime($end);
    }

    if($clock_in > $start){
        $late_arrival = 1; 
    }

    if($clock_out < $end){
        $early_dep = 1;
    }

    $dita = date('l', strtotime($_POST['currDate']));
    if(($dita == 'Saturday') || ($dita == 'Sunday')){
        $q = "INSERT INTO attendances (emp_id, curr_date, clock_in, clock_out, hr_worked, overtime, late_arrival, early_leave)
              VALUES('{$_SESSION['emp_id']}','{$_POST['currDate']}', '$clock_in', '$clock_out', '$hr_worked', '$hr_worked', '$late_arrival', '$early_dep')";
    }else{
        $q = "INSERT INTO attendances (emp_id, curr_date, clock_in, clock_out, hr_worked, overtime, late_arrival, early_leave)
              VALUES('{$_SESSION['emp_id']}','{$_POST['currDate']}', '$clock_in', '$clock_out', '$hr_worked', '$overtime', '$late_arrival', '$early_dep')";
    }

    $r = @mysqli_query($dbc, $q); 
    if(mysqli_affected_rows($dbc) == 1){
        echo "<div class='sukses'>Thank you for coming in today!</div>";
    }else{
        echo "<div class='error'>Error inserting data! Please contact manager.</div>";
    }
}
?>
<div class="box">
    <form action="" method="POST" id="clockForm">
    <br>
    <div class="input-group input-group-lg">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar" aria-hidden="true">
            </span>
        </span>	
        <?php $date = date('Y-m-d'); 
            echo '<input type="date" name="currDate" id="data" class="form-control" readonly value="'.$date.'">';
        ?>
        
    </div>
    <div class="inout btn1">
        <input type="text" name="in" class="btn" id="butoni1" value="Clock In">
    </div>

    <div class="inout btn2">
    <input type="text" name="out" class="btn" id="butoni2" value="Clock Out">
    </div>
    <input type="hidden" id="cin" name="cin">
    <input type="hidden" id="cout" name="cout">
    </form>
</div>
<script>
    $(document).ready(function() {
        var emp = <?php echo json_encode($_SESSION['emp_id']) ?>;
        var flag = <?php echo json_encode($flag) ?>;
        
        $('.btn').on('click', function() {
            $(this).toggleClass('is-clicked');
        });

        if(flag == 1){
            alert('You already clocked in for today');
            $('#butoni2').attr('disabled','disabled');
            $('#butoni1').attr('disabled','disabled');
        }
        
        if(!localStorage.getItem(emp)){
            $('#butoni2').attr('disabled','disabled');
            
            $('#butoni1').on('click', function(){
                var dt = new Date();
                let cin = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
                localStorage.setItem(emp,cin);
                $(this).attr('disabled', 'disabled');
                $('#butoni2').removeAttr('disabled');
            });

            $('#butoni2').on('click', function(){
                var dt = new Date();
                var cout = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
                $('#cout').val(cout);
                $('#cin').val(localStorage.getItem(emp));
                $('#clockForm').submit();
                localStorage.removeItem(emp);
            });
        }else{
            $('#butoni1').attr('disabled','disabled');
            $('#butoni2').on('click', function(){
                var dt = new Date();
                var cout = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
                $('#cout').val(cout);
                $('#cin').val(localStorage.getItem(emp));
                $('#clockForm').submit();
                localStorage.removeItem(emp);
            });
        }
    });
</script>
<?php
include('includes/footer.html');
?>
