<?php
$title = "Attendance Report";
include('includes/header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: index.php');
}else{
    require('includes/tracker_connect.php');
    $date = date('F');
    echo '
    <div class="box"> 
        <div class="page-header"
            <h4> <b> ATTENDANCES REPORT FOR '. strtoupper($date).'</b> </h4>
        </div>
    ';
$q = "SELECT CONCAT(b.fname,' ',b.lname) as emp, a.curr_date, a.clock_in, a.clock_out, a.hr_worked, a.overtime 
      FROM attendances AS a
            INNER JOIN 
          users AS b USING(emp_id)
      WHERE (MONTH(curr_date) = MONTH(CURRENT_DATE()))";
$r = @mysqli_query($dbc, $q);
}
?>

<div class="col-md-3">  
    <input placeholder="From Date" type="text" name="from_date" id="from_date" class="form-control" onfocus = "(this.type = 'date')" onblur = "(this.type = 'text')"/>  
</div>  

<div class="col-md-3">  
    <input placeholder="To Date" type="text" name="to_date" id="to_date" class="form-control" onfocus = "(this.type = 'date')" onblur = "(this.type = 'text')"/>  
</div>   

<div style="clear:both"></div>                 
<br/>
<div class="col-md-6">
    <input type="text" name="search_text" id="search_text" placeholder="Search by Details" class="form-control" />
</div>

<div class="col-md-6">  
    <input type="button" name="filter" id="filter" value="Filter" class="btn-sm btn-primary" />  
</div> 
<br>
<br>
<br>
<div id="order_table">  
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
        <?php 
while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
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
?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function(){
        $('#filter').click(function(){  
            var from_date = $('#from_date').val();  
            var to_date = $('#to_date').val();  
            var search = $('#search_text').val();
            if(from_date<=to_date){  
                $.ajax({  
                    url:"search.php",  
                    method:"POST",  
                    data:{query:search, from_date:from_date, to_date:to_date},  
                    success:function(data)  
                    {  
                        $('#order_table').html(data);  
                    }  
                });  
            }else if(from_date > to_date){
                alert("Start Date MUST precede End Date");
            }else{  
                    alert("Please Select Both Dates");  
            }  
        });
    }); 
</script>
<?php
include('includes/footer.html');
?>