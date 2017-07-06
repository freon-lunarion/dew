<?php $this->load->view('_base/top');?>
<h1 class="page-header">Measurement <small>Change Value</small></h1>
<?php echo form_open($process, 'class="form"'); ?>
  <div class="form-group">
    <label for="dt_begin">Since </label>
    <input type="date" class="form-control" name="dt_begin" id="dt_begin" value="<?php echo date('Y-m-d')?>" >
  </div>

  <div class="form-group">
    <label for="">Type</label>

    <div class="radio">
      <label><input type="radio" name="rd_type" value="MAX" <?php echo $typeMax ; ?>/>Maximize</label>
    </div>
    <div class="radio">
      <label><input type="radio" name="rd_type" value="MIN" <?php echo $typeMin ; ?>/>Minimize</label>
    </div>
    <div class="radio">
      <label><input type="radio" name="rd_type" value="STA" <?php echo $typeSta ; ?>/>Stabilize</label>
    </div>

  </div>
  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
