<div class="row" >
    <div class="col-md-3"><!-- To add something on the left of the form do it in this div --></div>
    <div class="col-md-6 frmDriverRegister form-group">
        <h2>Driver Registration</h2>
        <form action="<?php echo base_url('drivers/signup/' . $userId); ?>" method="POST" enctype="multipart/form-data">
            <div class=" col-md-12 " >
                <input type="text" class=" txtRad col-md-6" name="idNumber" placeholder="ID Number" required="" >
                <input type="file" name="idDocument" class="col-md-6" style="color: white;">
                <?php echo form_error('idnumber', '<span class="help-block">', '</span>'); ?>
            </div>
            <div class=" col-md-12 " >
                <input type="text" class="txtRad  col-md-6" name="licenceNumber" placeholder="License Number" required="" >
                <input type="file" name="licenceDocument" class="col-md-6" style="color: white;">
                <?php echo form_error('driversLicense', '<span class="help-block">', '</span>'); ?>
            </div>
            <div class=" col-md-12 " >
                <input type="text" class=" txtRad col-md-6" name="drivingExperience" placeholder="Driving Experience" required="" >
                <?php echo form_error('drivingExperience', '<span class="help-block">', '</span>'); ?>
            </div>
            <div class=" col-md-12 " >
                <label class=" col-md-6"> Criminal Record?</label>
                <span class="row form-check form-check-inline col-md-6">
                    <label for="Yes" class="radio-inline col-md-3"><input type="radio" name="criminalRecord" value="yes">Yes</label>
                    <label for="No" class="radio-inline col-md-3"><input type="radio" name="criminalRecord" value="no">No</label>
                </span>
            </div>
             <div class=" col-md-12 " >
                <label for="facePhoto" class="col-md-6">Face Photo</label>
                <input type="file" name="facePhoto" class="col-md-6" style="color: white;">
                <?php echo form_error('facePhoto', '<span class="help-block">', '</span>'); ?>
            </div>
            <div class=" col-md-12 " >
                <label for="crearence" class="col-md-6">Clearence Certificate</label>
                <input type="file" name="clearanceCertificate" class="col-md-6" style="color: white;">
                <?php echo form_error('clearenceCertificate', '<span class="help-block">', '</span>'); ?>
            </div>
           
            <div class=" col-md-12 " >
                <label for="pdp" class="col-md-6">PDP</label>
                <input type="file" name="pdp" class="col-md-6" style="color: white;">
                <?php echo form_error('pdp', '<span class="help-block">', '</span>'); ?>
            </div>
            <div class=" col-md-12 ">
                <lable  class=" col-md-6" name="area"  required="" >Area</lable>
                <select name="Area" class="col-md-6" style="color: black;">
                    <option value="selectOption">Select an Option</option>
                    <option value="sandton">Sandton</option>
                    <option value="midrand">Midrand</option>
                    <option value="fourways">Fourways</option>
                    <option value="soweto">Soweto</option>
                    <option value="tembisa">Tembisa</option>
                    <option value="eastrand">East Rand</option>
                    <option value="kemptonpark">Kempton Park</option>
                    <option value="randburg">Randburg</option>
                    <option value="citycenter">City Center</option>
                </select>
            </div>
            <div class=" col-md-12 ">
                <lable  class=" col-md-6" name="vehicle type"  required="" >vehicle Type</lable>
                <select name="Area" class="col-md-6" style="color: black;">
                    <option value="selectOption">Select an Option</option>
                    <option value="bike">Motobike</option>
                    <option value="car">Car</option>
                    <option value="bakkie">Bakkie</option>
                    <option value="truck">Truck</option>
                </select>
            </div>
                <div class=" col-md-12 ">
                    <input type="text" class="txtRad col-md-6" name="vehiclelicence" placeholder="Vehicle Licence" required="" >
                    <?php echo form_error('driversLicense', '<span class="help-block">', '</span>'); ?>
                </div>
                <div class=" col-md-12 ">
                    <input type="text" class="txtRad  col-md-6" name="car year" placeholder="Car Year" required="" >
                    <?php echo form_error('carYear', '<span class="help-block">', '</span>'); ?>
                </div>
           
            
             <input type="submit" name="signup" class="btn btn-block"  value="Submit"/>
        </form>
    </div>

    <div class="col-md-3"><!-- To add something on the right of the form do it in this div --></div>
</div>
