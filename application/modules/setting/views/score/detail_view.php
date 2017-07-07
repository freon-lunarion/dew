<?php $this->load->view('_base/top');?>
<?php echo anchor($backLink,'Back','class="btn btn-default"');?>
<h1 class="page-header">Score <small>View</small></h1>

<h2>Detail</h2>
<dl class="">
  <dt>Begin - End</dt>
  <dd>{begin} - {end}</dd>
  <dt>Score Value</dt>
  <dd style="color:{color}">{value}</dd>
  <dt>Category</dt>
  <dd style="color:{color}">{category}</dd>
  <dt>Lower - Upper</dt>
  <dd>{lower} - {upper}</dd>
</dl>

<?php echo anchor($backLink,'Back','class="btn btn-default"');?> <?php echo anchor($editLink,'Edit','class="btn btn-default"');?> <?php echo anchor($delLink,'Delete','class="btn btn-danger btn-delete"');?>

<?php $this->load->view('_base/bottom');?>
<script src="<?php echo base_url()?>assets/js/filterDate.js"></script>
