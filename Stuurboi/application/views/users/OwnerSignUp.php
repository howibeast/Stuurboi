<div class="row" >
    <div class="col-md-3"><!-- To add something on the left of the form do it in this div --></div>
    <div class="col-md-6 frmDriverRegister form-group">
        <h2>Owner Registration</h2>
        <form action="<?php echo base_url('owners/signup/' . $userId); ?>" method="POST" enctype="multipart/form-data">
           
             <div class=" col-md-12 " >
                <input type="text" class="txtRad col-md-6" name="idNumber" placeholder="ID Number" required="" >
                <input type="file" name="idDocument" class="col-md-6">
                <?php echo form_error('idNumber', '<span class="help-block">', '</span>'); ?>
            </div>
            
            <div class=" col-md-12 " >
                <input type="text" class="txtRad col-md-6" name="licensePlate" placeholder="License Plate" required="" >
                <?php echo form_error('licensePlate', '<span class="help-block">', '</span>'); ?>
            </div>
            
            <div class=" col-md-12 " >
                <input type="text" class="txtRad col-md-6" name="model" placeholder="Car Model" required="" >
                <?php echo form_error('model', '<span class="help-block">', '</span>'); ?>
            </div>
            
            <div class=" col-md-12 " >
                <input type="text" class="txtRad col-md-6" name="vehicleType" placeholder="Vehicle Type" required="" >
                <?php echo form_error('vehicleType', '<span class="help-block">', '</span>'); ?>
            </div>
            <div class=" col-md-12 " >
                <label class=" col-md-6"> Criminal Record?</label>
                <span class="row form-check form-check-inline col-md-6">
                    <label for="Yes" class="radio-inline col-md-3"><input type="radio" name="criminalRecord" value="yes">Yes</label>
                    <label for="No" class="radio-inline col-md-3"><input type="radio" name="criminalRecord" value="no">No</label>
                </span>
            </div>
            
            <div class=" col-md-12 ">
                <label for="clearenceDocument" class="col-md-6">Clearence Certificate</label>
                <input type="file" name="clearenceDocument" class="col-md-6">
                <?php echo form_error('clearenceDocument', '<span class="help-block">', '</span>'); ?>
            </div>
           
            <div class=" col-md-12 " >
                <label for="facePhoto" class="col-md-6">Upload Face Photo</label>
                <input type="file" name="facePhoto" class="col-md-6">
                <?php echo form_error('facePhoto', '<span class="help-block">', '</span>'); ?>
            </div>
           
            
            <input type="submit" value="signup" name="signup">
        </form>
    </div>

    <div class="col-md-3"><!-- To add something on the right of the form do it in this div --></div>
</div>
