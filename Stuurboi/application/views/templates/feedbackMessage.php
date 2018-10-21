<?php if(!empty($this->session->flashdata('message'))): ?>
<div class="alert text-center alert-<?php echo $this->session->flashdata('messageType'); ?> col-md-10 col-md-offset-1 alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times; </a>
    <?php echo $this->session->flashdata('message'); ?>
</div>
<?php endif; ?>