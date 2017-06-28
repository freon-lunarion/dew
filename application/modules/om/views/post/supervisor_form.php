<?php $this->load->view('_base/top');?>
<h1 class="page-header">Position <small>Change Supervisor</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <div class="form-group">
    <label for="dt_begin">Since </label>
    <input type="date" class="form-control" name="dt_begin" id="dt_begin" value="<?php echo date('Y-m-d')?>" >
  </div>

  <div class="form-group">
    <label for="txt_name">Supervisor</label>
    <?php $this->load->view('_element/orgPostStruct_input');?>


  </div>
  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
<?php $this->load->view('_element/orgPostStruct_modal');?>
