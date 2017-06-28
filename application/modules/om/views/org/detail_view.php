<?php $this->load->view('_base/top');?>
<?php echo anchor($backLink,'Back','class="btn btn-default"');?>
<h1 class="page-header">Organization <small>View</small></h1>

<?php $this->load->view('_element/rangedate_filter.php'); ?>

<div class="refresh_target" data-ajax="{ajaxUrl1}"></div>
<div class="refresh_target" data-ajax="{ajaxUrl2}"></div>

<?php echo anchor($backLink,'Back','class="btn btn-default"');?> <?php echo anchor($delLink,'Delete','class="btn btn-danger btn-delete"');?>
<?php $this->load->view('_base/bottom');?>
<script src="<?php echo base_url()?>assets/js/filterDate.js"></script>
