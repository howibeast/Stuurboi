<h2>Dear Buyer</h2>
<p>Your payment was successful, thank you for purchase.</p>
<p>Item Number : <b><?php echo $item_number; ?></b></p>
<p>TXN ID : <b><?php echo $txn_id; ?></b></p>
<p>Amount Paid : <b>$<?php echo $payment_amt.' '.$currency_code; ?></b></p>
<p>Payment Status : <b><?php echo $status; ?></b></p>
<a href="<?php echo base_url('products'); ?>">Back to Products</a>