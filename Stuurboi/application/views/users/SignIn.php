<div class="row" >
    <div class="col-md-4 col-xs-2"><!-- To add something on the left of the form do it in this div --></div>
    <div class="col-md-4 col-xs-8 frmLogin form-group">
        <h2>User Login</h2>
        <?php
        if (!empty($success_msg)) {
            echo '<p class="statusMsg">' . $success_msg . '</p>';
        } elseif (!empty($error_msg)) {
            echo '<p class="statusMsg">' . $error_msg . '</p>';
        }
        ?>
        <form action="<?php echo base_url('users/signin'); ?>" method="post">
            <div class="form-group has-feedback">
                <input type="email" class="form-control" name="email" placeholder="Email" required="" value="">
                <?php echo form_error('email', '<span class="help-block">', '</span>'); ?>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required="">
                <?php echo form_error('password', '<span class="help-block">', '</span>'); ?>
            </div>
            <div class="form-group">
                <input type="submit" name="singin" class="btn btn-block" value="SignIn" style="color: black;"/>
                
            </div>
        </form>
        <p class="footInfo">Don't have an account? <a href="<?php echo base_url(); ?>users/signup">Register here</a></p>
    </div>
    <div class="col-md-4"><!-- To add something on the right of the form do it in this div --></div>
</div>