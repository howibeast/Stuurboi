<div class="tableHeader col-lg-9">
    <!--  Payments VIEW -->
    <h3 class="text-center"> Payment Methods </h3>     
    <div class="row" style="background-color: #ffffff;">
        <?php if (count($paymentMethods) > 0): ?>
            <?php foreach ($paymentMethods as $paymentMethod): ?>
                <div class="panel panel-default col-md-10 col-md-offset-1" style="margin-top: 20px;">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <h5 class="col-md-6"><b>Card Name </b>: <?php echo $paymentMethod->nameOnCard; ?></h5><span class="col-md-6"> <b>Card Number </b>: *********<?php echo substr($paymentMethod->cardNumber, 8); ?></span>
                                </div>
                                <div class="row">
                                    <span  class="col-md-6"> <b>expiry date : </b><?php echo $paymentMethod->expiryMonth . '/' . $paymentMethod->expiryYear; ?></span>
                                    <span class="col-md-6"> 
                                        <form action="<?php echo base_url('payments/confirmPayment'); ?>" method="POST">
                                            <input type="hidden" name="methodId" value="<?php echo $paymentMethod->id ?>">
                                            <input type="submit" class="btn btn-block" value="Pay"> 
                                        </form>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="row">
                <span class="col-md-6"><a href="" class="btn btn-block btn-primary ">Add Paypal</a></span><span class="col-md-6"> <a href="<?php echo base_url('payments/addCard'); ?>" class="btn btn-block btn-primary">Add Card</a></span>
            </div>
        <?php else : ?>
            <div class="panel panel-default col-md-10 col-md-offset-1" style="margin-top: 20px;">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                <span class="col-md-6"><a href="" class="btn btn-block btn-primary">Add Paypal</a></span><span class="col-md-6"> <a href="<?php echo base_url('payments/addCard'); ?>" class="btn btn-block btn-primary">Add Card</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!--     -->  
    <div class="row">

    </div>
    <!-- /.row -->

</div>
<!-- /.col-lg-9 -->


