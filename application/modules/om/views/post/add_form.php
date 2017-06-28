<?php $this->load->view('_base/top');?>
<h1 class="page-header">Position <small>Add</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <?php $this->load->view('_element/add_form');?>
  <div class="form-group">
    <label for="txt_name">Parent Organization</label>
    <?php $this->load->view('_element/orgStruct_input');?>

  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox" name="chk_chief" id="chk_chief"> Chief
    </label>
  </div>
  <div class="form-group">
    <label for="txt_name">Supervisor</label>
    
    <?php $this->load->view('_element/orgPostStruct_input');?>

  </div>
  <div class="form-group">
    <label for="txt_name">Job Type</label>
    <?php echo form_dropdown('slc_job',$jobOpt, '','id="slc_job" class="form-control"'); ?>
  </div>
  <div class="form-group">
    <label for="txt_name">Employee</label>
    <?php echo form_dropdown('slc_emp',$empOpt, '','id="slc_emp" class="form-control"'); ?>
  </div>
  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
<?php $this->load->view('_element/orgStruct_modal');?>
<?php $this->load->view('_element/orgPostStruct_modal');?>
