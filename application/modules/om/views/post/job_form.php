<?php $this->load->view('_base/top');?>
<h1 class="page-header">Job <small>Change Job</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <div class="form-group">
    <label for="dt_begin">Since </label>
    <input type="date" class="form-control" name="dt_begin" id="dt_begin" value="<?php echo date('Y-m-d')?>" >
  </div>

  <div class="form-group">
    <label for="txt_name">Job Type</label>
    <?php echo form_dropdown('slc_job',$jobOpt, $jobSlc,'id="slc_job" class="form-control"'); ?>

  </div>
  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
