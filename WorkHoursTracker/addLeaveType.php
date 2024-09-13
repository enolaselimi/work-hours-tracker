<?php
$title = "Add Leave Type";
include('includes/header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: index.php');
}else{
    require('includes/tracker_connect.php');
    echo '
    <div class="box"> 
        <div class="page-header"
            <h4> <b> ADD LEAVE TYPE</b> </h4>
        </div>
    ';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $errors = []; 
        if(isset($_POST['type'])){
            $ltype =  mysqli_real_escape_string($dbc,$_POST['type']);
        }else{
            $errors[] = 'Please select a type of leave.';
        }

        if(isset($_POST['conf'])){
            $paid = $_POST['conf'];
        }else{
            $errors[] = 'Please select if this type of leave is paid or not.';
        }

        if(empty($errors)){
            $q = "INSERT INTO leavetypes(type, payment) VALUES('$ltype', '$paid')";
            $r = @mysqli_query($dbc, $q);
            if(mysqli_affected_rows($dbc) == 1){
                echo '<div class="sukses">Leave Type added successfully.</div>';
            }else{
                echo '<div class="error">The type could not be added due to a system error.<br>
                    We apologize for any inconvenience. </div>';
            }
        }else{
            echo '<div class="error">'.$errors[0].'</div>';
        }
    }
}
?>

<div id="boxDel"> 
    <form action="addLeaveType.php" method="POST" class="form-horizontal form" > 
        <div class="form-group">
            <label> Leave Type: </label><br>
            <input class="form-control form-control-lg" type="text" name="type" maxlength="60" value="<?php if(isset($_POST['type'])) echo $_POST['type']; ?>"><br><br>
            <label class="col-sm-6 control-label">Is this type of leave paid?</label>
                <div class="col-sm-5 radios">
                    <div class="radio radio-danger">
                        <input type="radio" name="conf" id="Radios1" value="yes">
                        <label>
                            Yes
                        </label>
                    </div>
                    <div class="radio radio-danger">
                        <input type="radio" name="conf" id="Radios2" value="no">
                        <label>
                            No
                        </label>
                    </div>  
                </div>                     
            </div>
            <div class="del">
                <button value="Add" name="submit" type="submit" class="btn-sm btn-primary">Add</button>
                <a href="settings.php">Cancel</a>
            </div>
        </form>
        </div>
<?php
include('includes/footer.html');
?>
