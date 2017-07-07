<?php $this->load->view('_base/top');?>
<h1 class="page-header">Person <small>Add</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <?php $this->load->view('_element/add_form');?>
  <?php $this->load->view('_element/orgPostStruct_input'); ?>
  <div class="form-group">
    <label for="txt_name">Weight</label>
    <input type="number" class="form-control" id="nm_weight" name="nm_weight" value="100">
  </div>
  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
<?php $this->load->view('_element/orgPostStruct_modal'); ?>
