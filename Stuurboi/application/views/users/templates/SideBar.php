<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<style >
    ul li a {
        float: left;
        padding: 15px;
        color: #dedede;
    }
    @media screen and (max-width: 900px) {
        .sidebar{
            width:100%;
            height: auto;
            position: relative;
            background-color: #252526;
        }
    }
    @media screen and (max-width: 900px) {
        .items{
            width:100%;
            height: auto;
            position: relative;
            background-color: white;
        }
    }
</style>

<?php $this->load->view('templates/header'); ?>
<?php if (isOnline()): ?>
    <nav class="col-md-3 " role="navigation">
        <div class="navbar-header  sidebar hidden-lg ">
            <button type="button" class="navbar-toggle" data-toggle="collapse"  data-target=".navbar-collapse" >
                <span class="icon-bar" style="background-color: white;"></span>
                <span class="icon-bar" style="background-color: white;"></span>
                <span class="icon-bar" style="background-color: white;"></span>
            </button>
            <ul  class="dd"style="list-style-type: none; margin: 0;padding: 0;">
                <li><a href="<?php echo base_url('users/dashboard'); ?>" >Home</a></li>
                <li><a href="<?php echo base_url('users/signout'); ?>">logout</a></li>
                <li><a href="">About us</a></li>
            </ul>
        </div>
        <div class="dd collapse navbar-collapse">
            <div class="  items" toggle="true" id="bs-sidebar-navbar-collapse-1">
                <img src="<?php echo base_url('res/images/profilePics/default.jpg'); ?>" width="100" height="100" class="img-circle" >
                <h4 class="list-group-item"><?php echo $_SESSION['name'] . " " . $_SESSION['surname'] ?></h4>
                <?php if ($_SESSION['userType'] == Constants::USER_DRIVER): ?>
                    <a href="<?php echo base_url('trips/driverTrips'); ?>" name="trips" class="list-group-item">My Trips</a>
					 <a href="<?php echo base_url('users/profile'); ?>" class="list-group-item">Profile</a>
                    <a href="<?php echo base_url('payments/paymentMethods'); ?>" class="list-group-item">Payment</a>
                    <a href="<?php echo base_url(); ?>" class="list-group-item">Account Settings</a>
                    <a href="<?php echo base_url(); ?>" class="list-group-item">Logout</a>
                    <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                        <i class="fa fa-bars"></i>
                    </a>
                <?php elseif ($_SESSION['userType'] == Constants::USER_CLIENT): ?>
                    <a href="<?php echo base_url('trips/userTrips'); ?>" name="trips" class="list-group-item">My Trips</a>
                    <a href="<?php echo base_url('users/profile'); ?>" class="list-group-item">Profile</a>
                    <a href="<?php echo base_url('payments/paymentMethods'); ?>" class="list-group-item">Payment</a>
                    <a href="<?php echo base_url(); ?>" class="list-group-item">Account Settings</a>
                    <a href="<?php echo base_url(); ?>" class="list-group-item">Logout</a>
                    <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                        <i class="fa fa-bars"></i>
                    </a>
                   
                <?php endif; ?>

                <?php if ($_SESSION['userType'] == Constants::USER_ADMIN): ?>
                    <a href="<?php echo base_url('tester/numberofusers'); ?>" name="numberofusers" class="list-group-item">Number of Users</a>
                    <a href="<?php echo base_url('tester/mostrequestedplaces'); ?>" name="mostrequestedplaces" class="list-group-item">Most Requested Places</a>
                    <a href="<?php echo base_url('tester/peakmonth'); ?>" name="Peak Month" class="list-group-item">Peak Month </a>
                   
                <?php endif; ?>
            </div>
        </div>
        <?php
    else:
        $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'You need to log in to access this page'));
        redirect('users/signin');

    endif;
    ?>
</nav>
