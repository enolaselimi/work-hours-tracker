<?php 

if(isset($errors) && !empty($errors)){
    echo "
    <div class='error'>$errors[0]</div>";
}
?>
<div class="starter-template">
<div class="page-header">
    <h3> Sign In </h3>
</div>
<form action="login.php" method="POST">
    <div class="controls col-md-12">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control form-control-lg" />
    </div>

    <div class="controls col-md-12">
        <label class="form-label" for="pass">Password</label>
        <input type="password" id="pass" name="pass" class="form-control form-control-lg" />
    </div>
    
    <div class="controls col-md-12">
        <br>
        <input type="submit" name="submit" value="Sign In">
    </div>
</form>

<?php include('includes/footer.html'); ?>