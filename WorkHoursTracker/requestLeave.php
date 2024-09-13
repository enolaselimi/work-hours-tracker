<?php
$title = "Request Leave";
include('includes/header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: index.php');
}else{
    require('includes/tracker_connect.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $errors = []; 
        if(isset($_POST['type'])){
            $ltype =  mysqli_real_escape_string($dbc,$_POST['type']);
        }else{
            $errors[] = 'Please select a type of leave.';
        }

        if(!empty($_POST['from']) && !empty($_POST['to'])){
            $from = $_POST['from'];
            $to = $_POST['to'];
        }else{
            $errors[] = 'Please specify both the start and end date.';
        }

        if(!empty($_POST['returnD'])){
            $return = $_POST['returnD'];
        }else{
            $errors = "Please specify when you will be back to work.";
        }

        $desc = $_POST['comm'];
        $status = 'pending';

        if(empty($errors)){
            $q = "INSERT INTO leaves(emp_id, type, from_date, to_date, return_date, comments, leave_status)
            VALUES('{$_SESSION['emp_id']}', '$ltype', '$from', '$to',  '$return', '$desc', '$status') ";

            $r = @mysqli_query($dbc, $q); 
            if(mysqli_affected_rows($dbc) == 1){
                echo '<div class="sukses">Request was sent! </div>';
                exit();
            }else{
                echo '<div class="error">Request could not be sent! </div>';
            }
        }else{
            echo '<div class="error">'.$errors[0].'</div>';
        }
    }

    echo'<div class="box"> 
        <div class="page-header"
            <h4> <b> REQUEST A LEAVE</b> </h4>
        </div>
        <form action="" method="POST">
            <div class="controls col-md-12">
                <label class="form-label" for="role">Leave Type:</label>
                <select class="form-control form-control-lg" name="type" id="type">
                    <option value="null" disabled selected> --- </option>
    ';
    $q = "SELECT * FROM leavetypes";
    $r = @mysqli_query($dbc, $q);
    if(mysqli_num_rows($r)>0){
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
            echo '<option value="'.$row['type_id'].'">'.$row['type'].'</option>';
        }
    }
    echo '</select> </div>';
}
?>
        <div class="controls col-md-6">
            <label class="form-label" for="from-date">From: </label>
            <input type="date" id="from-date" name="from" class="form-control form-control-lg" />
        </div>

        <div class="controls col-md-6">
            <label class="form-label" for="to-date">To:</label>
            <input type="date" id="to-date" name="to" class="form-control form-control-lg" />
        </div>

        <div class="controls col-md-12">
            <label class="form-label" for="returnD">Return Date: </label>
            <input type="date" id="returnD" name="returnD" class="form-control form-control-lg" />
        </div>
        <div class="controls col-md-12">
            <br>
            <label>Comments: (If you selected other please specify your reason below)</label><br>
            <textarea cols="150" rows="5" name="comm"><?php if(!empty($_POST['comm'])) echo $_POST['comm']; ?></textarea><br><br>
        </div>
        <input type="submit" value="Send"> 
        <button id="cancel-btn" class="btn-sm btn-default"> Cancel </button>
        </form>
<script>
    $('#cancel-btn').on('click', function(e){
        e.preventDefault();
        window.history.back();
    });
</script>
<?php
include('includes/footer.html');
?>