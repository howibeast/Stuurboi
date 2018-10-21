
<div class="row" >
    <div class="col-md-4 col-xs-3 frmDescription text-center"><!-- To add something on the left of the form do it in this div -->
        <h2> <b>Description</b> </h2>
    <h3> Client: </h3> A person who requests for their belongings to be moved.
    <h3> Driver: </h3> A person who wish to partner with Stuurboi and deliver either with their own car or someone else's.
    </div>
    <div class="col-md-4 col-xs-8 frmRegister form-group">
        <h2>User Registration</h2>
        
        <form action="<?php echo base_url('users/signup')?>" method="POST">


            <input type="text" class="form-control" name="name" placeholder="Name" required="" value="<?php echo!empty($user['name']) ? $user['name'] : ''; ?>">
            <?php echo form_error('name', '<span class="help-block">', '</span>'); ?>



            <input type="text" class="form-control" name="surname" placeholder="Surname" required="" value="<?php echo!empty($user['surname']) ? $user['surname'] : ''; ?>">
            <?php echo form_error('surname', '<span class="help-block">', '</span>'); ?>
            

            <span class="row form-check form-check-inline">
                <label for="Male" class="radio-inline"><input type="radio" name="gender" value="male">Male</label>
                <label for="Female" class="radio-inline"><input type="radio" name="gender" value="female">Female</label>
                <label for="Other" class="radio-inline"><input type="radio" name="gender" value="other">Other</label>
            </span>
            
            <input type="email" class="form-control" name="email" placeholder="Email" required="" value="<?php echo!empty($user['email']) ? $user['email'] : ''; ?>">
            <?php echo form_error('email', '<span class="help-block">', '</span>'); ?>
            
   
            <input type="tel" class="form-control" name="cellNumber" placeholder="Phone" value="<?php echo!empty($user['cellNumber']) ? $user['cellNumber'] : ''; ?>">
        
           <span class="row form-check form-check-inline">
                <label for="client" class="radio-inline"><input type="radio" name="userType" value="client">Client</label>
                <label for="driver" class="radio-inline"><input type="radio" name="userType" value="driver">Driver</label>
               </span>
            
            <input type="password" class="form-control" name="password" placeholder="Password" required="">
            <?php echo form_error('password', '<span class="help-block">', '</span>'); ?>

            <input type="password" class="form-control" name="passVerif" placeholder="Confirm password" required="">
            <?php echo form_error('conf_password', '<span class="help-block">', '</span>'); ?>
            <br/>
            <input type="submit" name="btnSignup" class=" btn btn-block" value="Submit" style="color: black;"/>
           
        </form>
        <br/>
        <p class="footInfo">Already have an account? <a href="<?php echo base_url('users/signin'); ?>">Login here</a></p>              
    </div>
    <div class="col-md-4"><!-- To add something on the right of the form do it in this div --></div>
</div>


