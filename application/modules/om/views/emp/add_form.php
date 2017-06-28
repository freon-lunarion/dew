<?php $this->load->view('_base/top');?>
<h1 class="page-header">Person <small>Add</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <?php $this->load->view('_element/add_form');?>
  <div class="form-group">
    <label for="txt_name">Position</label>
    <?php echo form_dropdown('slc_post',$postOpt, $postSlc,'id="slc_parent" class="form-control"'); ?>
  </div>
  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
