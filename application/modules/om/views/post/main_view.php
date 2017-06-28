<?php $this->load->view('_base/top');?>
<?php echo anchor('om','Main Menu', 'class="btn btn-default"');?>
<h1 class="page-header">Position <small></small></h1>
<?php echo anchor($addLink,'Add' ,'class="btn btn-default"');?>
<hr />
<?php $this->load->view('_element/rangedate_filter');?>
<div id="tbl_obj" class="refresh_target" data-ajax="{ajaxUrl}"></div>

<?php $this->load->view('_base/bottom');?>
<script src="<?php echo base_url()?>assets/js/filterDate.js"></script>
