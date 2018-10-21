<div class="row" >
    <div class="col-md-8 frmLogin form-group">
        <h2 class="text-center">Add Card</h2>
        <form action="<?php echo base_url('payments/addCard'); ?>" method="post">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="nameOnCard" placeholder="name on card" required="" value="">
                <?php echo form_error('email', '<span class="help-block">', '</span>'); ?>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="cardNumber" placeholder="Card Number" maxlength="13" required="" value="">
                <?php echo form_error('email', '<span class="help-block">', '</span>'); ?>
            </div>
            <div class="col-md-6" >
                <div class="form-group">
                    <div class="input-group date" id="datetimepicker10">
                        <input type="text" name="expiryMonth" placeholder="Expiry Month" class="form-control"/>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6" >
                <div class="form-group">
                    <div class="input-group date" id="datetimepicker10">
                        <input type="text" name="expiryYear" placeholder="Expiry Year" class="form-control"/>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="cvv" placeholder="CVV" maxlength="4" required="" value="">
                <?php echo form_error('email', '<span class="help-block">', '</span>'); ?>
            </div>
            <script type="text/javascript">
                $(function(){
                    $('#datetimepicker10').datepicker({
                        viewMode: 'years',
                        format: 'MM/YYYY'
                    });
                });
            </script>
            
            <div class="form-group">
                <input type="submit" name="addCard" class="btn btn-block" value="Add Card" style="color: black;"/>
            </div>
        </form>
    </div>
</div>