<?php $this->load->view('_base/top');?>
<h1 class="page-header">Person <small>Position</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <?php $this->load->view('_element/date_form');?>

  <?php $this->load->view('_element/orgPostStruct_input'); ?>

  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
<?php $this->load->view('_element/orgPostStruct_modal'); ?>
