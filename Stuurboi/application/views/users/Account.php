<!DOCTYPE html>
<html lang="en">  
<head>
<link href="<?php echo base_url(); ?>assets/css/style.css" rel='stylesheet' type='text/css' />
</head>
<body>
<div class="container">
    <h2>User Account</h2>
    <h3>Welcome <?php echo $user['name']; ?>!</h3>
    <div class="account-info">
        <p><b>Name: </b><?php echo $user['name']; ?></p>
        <p><b>Name: </b><?php echo $user['surname']; ?></p>
        <p><b>Gender: </b><?php echo $user['gender']; ?></p>
        <p><b>Email: </b><?php echo $user['email']; ?></p>
        <p><b>Phone: </b><?php echo $user['cellNumber']; ?></p>
        <p><b>Password: </b><?php echo $user['password']; ?></p>
        <p><b>Account Status: </b><?php echo $user['status']; ?></p>
        <p><b>date Created: </b><?php echo $user['dateCreated']; ?></p>
        <p><b>date Modified: </b><?php echo $user['dateCreated']; ?></p>
        
        
        
        
    </div>
    <form action="" method="post">
        <div class="form-group">
               <input type="submit" name="logout" class="btn-primary" value="Logout"/>
        </div>
    </form>
</div>
</body>
</html>
