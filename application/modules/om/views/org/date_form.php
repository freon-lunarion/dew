<?php $this->load->view('_base/top');?>
<h1 class="page-header">Organization <small>Change Date</small></h1>
<?php echo form_open($process, 'class="form"',$hidden); ?>

<?php $this->load->view('_element/date_form'); ?>
<?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
