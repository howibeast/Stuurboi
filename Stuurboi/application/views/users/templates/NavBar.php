<style >


</style>

<?php $this->load->view('templates/header'); ?>
<?php if (!isOnline()): ?>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li class="">
                    <a href="<?php echo base_url('users/home'); ?>">Home</a>
                </li>
                <li class="">
                    <a href="<?php echo base_url('users/signup'); ?>">Signup</a>
                </li>
                <li class="">
                    <a href="<?php echo base_url('users/signin'); ?>">Signin</a>
                </li>
                <li class="">
                    <a href="">About us</a>
                </li>
            </ul>
        </div>
    </nav>
<?php else: ?>
    <nav class="navbar navbar-inverse hidden-xs hidden-sm ">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li class="">
                    <a href="<?php echo base_url('users/dashboard'); ?>">Home</a>
                </li>
                <li class="">
                    <a href="<?php echo base_url('users/signout'); ?>">logout</a>
                </li>

                <li class="nomayiphi" style="display: none ;">
                    <?php if ($_SESSION['userType'] == Constants::USER_DRIVER): ?>
                        <a href="<?php echo base_url('trips/driverTrips'); ?>" name="trips" >My Trips</a>
                        <a href="<?php echo base_url('users/profile'); ?>" >Profile</a>
                        <a href="<?php echo base_url('payments/paymentMethods'); ?>" >Payment</a>
                    <?php elseif ($_SESSION['userType'] == Constants::USER_CLIENT): ?>
                        <a href="<?php echo base_url('trips/userTrips'); ?>" name="trips" >My Trips</a>
                        <a href="<?php echo base_url('users/profile'); ?>" >Profile</a>
                        <a href="<?php echo base_url('payments/paymentMethods'); ?>" >Payment</a>
                    <?php elseif ($_SESSION['userType'] == Constants::USER_ADMIN): ?>
                        <a href="<?php echo base_url('tester/numberofusers'); ?>" name="numberofusers" >Number of Users</a>
                        <a href="<?php echo base_url('tester/mostrequestedplaces'); ?>" name="mostrequestedplaces">Most Requested Places</a>
                        <a href="<?php echo base_url('tester/peakmonth'); ?>" name="Peak Month" >Peak Month </a>

                    <?php endif; ?>
                </li>
                <li class="nomayiphi" style="display: none ;">


                </li> 
                <li class="nomayiphi" style="display: none ;">

                </li>


            </ul>
            <ul class="nav navbar-nav  navbar-right">
                <li>
                    <img src="" width="10" height="10" class="img-circle" >

                </li>
                <li>
                    <!---Place the right dropdown here------->
                </li>
            </ul>
        </div>
    </nav>


<?php endif; ?>
<?php $this->load->view('templates/feedBackMessage'); ?>


