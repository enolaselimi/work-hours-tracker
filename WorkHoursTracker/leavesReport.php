<?php
$title = "Leaves Report";
include('includes/header.html');
if(!isset($_SESSION['emp_id']) || !isset($_SESSION['role'])){
    header('location: index.php');
}else{
    require('includes/tracker_connect.php');
    $date = date('F');
    echo '
    <div class="box"> 
        <div class="page-header"
            <h4> <b> LEAVES REPORT FOR '. strtoupper($date).'</b> </h4>
        </div>
    ';
    $q = "SELECT CONCAT(c.fname,' ',c.lname) AS emp, a.type, b.from_date, b.to_date, b.return_date, b.comments, b.leave_status 
          FROM leavetypes AS a 
            INNER JOIN 
           leaves AS b ON a.type_id = b.type
            INNER JOIN 
           users AS c ON b.emp_id = c.emp_id
          ";
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
                <th>Leave Type</th>
                <th>From date</th>
                <th>To date</th>
                <th>Return date</th>
                <th>Comments</th>
                <th>Status</th>
            </tr> 
        </thead>
        <tbody id="export_table">
        <?php 
while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
    echo '
    <tr>
    <td align="left">'.$row['emp'].'</td>
    <td align="left">'.$row['type'].'</td>
    <td align="left">'.$row['from_date'].'</td>
    <td align="left">'.$row['to_date'].'</td>
    <td align="left">'.$row['return_date'].'</td>
    <td align="left">'.$row['comments'].'</td>
    <td align="left">'.$row['leave_status'].'</td>
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
                    url:"searchLeaves.php",  
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