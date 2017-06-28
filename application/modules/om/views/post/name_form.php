<?php $this->load->view('_base/top');?>
<h1 class="page-header">Position <small>Change Name</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

<?php $this->load->view('_element/name_form'); ?>

<?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
