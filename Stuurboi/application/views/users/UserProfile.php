
<form action="<?php echo base_url('') ?>" method="POST">
    <div class=" col-md-9">
        <!--  PROFILE VIEW -->
        <div class="col-md-8 tableHeader form-group">
        <h3 > Profile </h3>     
        <div class="row" style="background-color: #ffffff;">
            <table class="table table-dark">
                <tr>
                <div class="form-group has-feedback">
                    <img src="<?php echo base_url('res/images/profilePics/default.jpg'); ?>" width="100" height="100" class="img-circle" >
                     <input type="file" name="uploadpicture" style="color: white;">
                </div> 
                </tr>
                <tr>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="editName" placeholder="" required="" value="<?php echo $user->name ?>">
                </div> 
                </tr>
                <tr>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="editSurname" placeholder="" required="" value="<?php echo $user->surname ?>">
                </div> 
                </tr>
                <tr>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="mobileNumber" placeholder="" required="" value="<?php echo $user->cellNumber ?>">
                </div> 
                </tr>
                <tr>
                <div class="form-group has-feedback">
                    <input type="submit" name="btnsave" class=" btn btn-block" value="Save" style="color: black;"/>
           </div> 
                </tr>
            </table>
        </div>
        <!--     -->  
        <div class="row">

        </div>
        <!-- /.row -->
        
    </div>
    <!-- /.col-lg-9 -->
    
</div>
</form>
    


