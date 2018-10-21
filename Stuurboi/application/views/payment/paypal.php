
<script>

    function cardFormValidate() {
        var cardValid = 0;

        //card number validation
        $('#card_number').validateCreditCard(function (result) {
            var cardType = (result.card_type == null) ? '' : result.card_type.name;
            if (cardType == 'Visa') {
                var backPosition = result.valid ? '2px -163px, 260px -87px' : '2px -163px, 260px -61px';
            } else if (cardType == 'MasterCard') {
                var backPosition = result.valid ? '2px -247px, 260px -87px' : '2px -247px, 260px -61px';
            } else if (cardType == 'Maestro') {
                var backPosition = result.valid ? '2px -289px, 260px -87px' : '2px -289px, 260px -61px';
            } else if (cardType == 'Discover') {
                var backPosition = result.valid ? '2px -331px, 260px -87px' : '2px -331px, 260px -61px';
            } else if (cardType == 'Amex') {
                var backPosition = result.valid ? '2px -121px, 260px -87px' : '2px -121px, 260px -61px';
            } else {
                var backPosition = result.valid ? '2px -121px, 260px -87px' : '2px -121px, 260px -61px';
            }
            $('#card_number').css("background-position", backPosition);
            if (result.valid) {
                $("#card_type").val(cardType);
                $("#card_number").removeClass('required');
                cardValid = 1;
            } else {
                $("#card_type").val('');
                $("#card_number").addClass('required');
                cardValid = 0;
            }
        });

        //card details validation
        var cardName = $("#name_on_card").val();
        var expMonth = $("#expiry_month").val();
        var expYear = $("#expiry_year").val();
        var cvv = $("#cvv").val();
        var regName = /^[a-z ,.'-]+$/i;
        var regMonth = /^01|02|03|04|05|06|07|08|09|10|11|12$/;
        var regYear = /^2017|2018|2019|2020|2021|2022|2023|2024|2025|2026|2027|2028|2029|2030|2031$/;
        var regCVV = /^[0-9]{3,3}$/;
        if (cardValid == 0) {
            $("#card_number").addClass('required');
            $("#card_number").focus();
            return false;
        } else if (!regMonth.test(expMonth)) {
            $("#card_number").removeClass('required');
            $("#expiry_month").addClass('required');
            $("#expiry_month").focus();
            return false;
        } else if (!regYear.test(expYear)) {
            $("#card_number").removeClass('required');
            $("#expiry_month").removeClass('required');
            $("#expiry_year").addClass('required');
            $("#expiry_year").focus();
            return false;
        } else if (!regCVV.test(cvv)) {
            $("#card_number").removeClass('required');
            $("#expiry_month").removeClass('required');
            $("#expiry_year").removeClass('required');
            $("#cvv").addClass('required');
            $("#cvv").focus();
            return false;
        } else if (!regName.test(cardName)) {
            $("#card_number").removeClass('required');
            $("#expiry_month").removeClass('required');
            $("#expiry_year").removeClass('required');
            $("#cvv").removeClass('required');
            $("#name_on_card").addClass('required');
            $("#name_on_card").focus();
            return false;
        } else {
            $("#card_number").removeClass('required');
            $("#expiry_month").removeClass('required');
            $("#expiry_year").removeClass('required');
            $("#cvv").removeClass('required');
            $("#name_on_card").removeClass('required');
            $('#cardSubmitBtn').prop('disabled', false);
            return true;
        }
    }
    $(document).ready(function () {

        //initiate validation on input fields
        $('#paymentForm input[type=text]').on('keyup', function () {
            cardFormValidate();
        });

        //submit card form
        $("#cardSubmitBtn").on('click', function () {
            if (cardFormValidate()) {
                var formData = $('#paymentForm').serialize();
                $.ajax({
                    type: 'POST',
                    url: 'payment_process.php',
                    dataType: "json",
                    data: formData,
                    beforeSend: function () {
                        $("#cardSubmitBtn").val('Processing....');
                    },
                    success: function (data) { //console.log(data);
                        if (data.status == 1) {
                            $('#orderInfo').html('<p>The transaction was successful. Order ID: <span>' + data.orderID + '</span></p>');
                            $('#paymentSection').slideUp('slow');
                            $('#orderInfo').slideDown('slow');
                        } else {
                            $('#orderInfo').html('<p>Transaction has been failed, please try again.</p>');
                            $('#paymentSection').slideUp('slow');
                            $('#orderInfo').slideDown('slow');
                        }
                    }
                });
            }
        });
    });
</script>

<div class="card-payment" style="background: background">
    <div id="paymentSection" >

        <h4>Payable amount: $10 USD</h4>
        <?php
        if (!empty($success_msg)) {
            echo '<p class="statusMsg">' . $success_msg . '</p>';
        } elseif (!empty($error_msg)) {
            echo '<p class="statusMsg">' . $error_msg . '</p>';
        }
        ?>
        <form action="<?php echo base_url('paypal/sendipn'); ?>" method="post">

            <div class="form-group has-feedback">
                <label for="cardNumber">Card number</label>
                <input type="text" placeholder="1234 5678 9012 3456" id="card_number" name="cardNumber">
                <?php echo form_error('cardNumber', '<span class="help-block">', '</span>'); ?>
            </div>

            <div class="form-group">
                <label for="expiryMonth">Expiry month</label>
                <input type="text" placeholder="MM" maxlength="5" id="expiry_month" name="expiryMonth">
                <?php echo form_error('expiryMonth', '<span class="help-block">', '</span>'); ?>
            </div>

            <div class="form-group">
                <label for="expiryYear">Expiry year</label>
                <input type="text" placeholder="YYYY" maxlength="5" id="expiry_year" name="expiryYear">
                <?php echo form_error('expiryYear', '<span class="help-block">', '</span>'); ?>
            </div>

            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" placeholder="123" maxlength="3" id="cvv" name="cvv">
                <?php echo form_error('cvv', '<span class="help-block">', '</span>'); ?>
            </div>

            <div class="form-group">
                <label for="nameOnCard">Name on card</label>
                <input type="text" placeholder="Codex World" id="name_on_card" name="nameOnCard">
                <?php echo form_error('nameOnCard', '<span class="help-block">', '</span>'); ?>
            </div>

            <div class="form-group">
                <input type="hidden" name="cardType" id="card_type" value=""/>
                <input type="submit" name="cardSubmit" id="cardSubmitBtn" value="Proceed" class="payment-btn">
            </div>
        </form>
    </div>
    <div id="orderInfo" style="display: none;"></div>
</div>