
<?php if (isOnline()): ?>
<div class="row" >
    <div class="col-md-4"><!-- To add something on the left of the form do it in this div --></div>
    <div class="col-md-4 frmLogin form-group">
    <form action="" method="post">
        <h2>Will you be driving your own car?</h2>
             <div class="form-group">
                 <a  href="<?php echo base_url('drivers/signup/'.$_SESSION['userId'])?>" name="driverandowner" class="btn btn-block btn-default">YES</a>
            </div>
             <div class="form-group">
                <a  href="<?php echo base_url('owners/signup/'.$_SESSION['userId'])?>" name="driveronly" class="btn btn-block btn-default">NO</a>
            </div>
        </form>
        </div>
    <div class="col-md-4"><!-- To add something on the right of the form do it in this div --></div>
</div>
<?php
else:
    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'You need to log in to access this page'));
    redirect('users/signin');
endif;
?>

