<?php $this->load->view('_base/top');?>
<h1 class="page-header">Formula <small>Add</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <?php $this->load->view('_element/add_form');?>
  <div class="form-group">
    <label for="">Type</label>

    <div class="radio">
      <label><input type="radio" name="rd_type" value="MAX"/>Maximize</label>
    </div>
    <div class="radio">
      <label><input type="radio" name="rd_type" value="MIN"/>Minimize</label>
    </div>
    <div class="radio">
      <label><input type="radio" name="rd_type" value="STA"/>Stabilize</label>
    </div>

  </div>
  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
