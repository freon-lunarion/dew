<?php $this->load->view('_base/top');?>
<h1 class="page-header">Formula  <small>Score</small></h1>
<?php echo form_open($process, 'class="form"',$hidden); ?>

  <div class="form-group">
    <label for="">Value</label>
    <input type="number" class="form-control" id="nm_value" name="nm_value" min="0" max="5" step="1" value=<?php echo $scoreValue ?>>
  </div>
  <div class="form-group">
    <label for="">Lower Bound</label>
    <input type="number" class="form-control" id="nm_lower" name="nm_lower" value=<?php echo $scoreLower ?>>
  </div>
  <div class="form-group">
    <label for="">Upper Bound</label>
    <input type="number" class="form-control" id="nm_upper" name="nm_upper"   value=<?php echo $scoreUpper ?>>
  </div>

  <?php $this->load->view('_element/date_form'); ?>

  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
