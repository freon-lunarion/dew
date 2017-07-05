<?php $this->load->view('_base/top');?>
<h1 class="page-header">Measurement <small>Change Value</small></h1>
<?php echo form_open($process, 'class="form"'); ?>
  <div class="form-group">
    <label for="dt_begin">Since </label>
    <input type="date" class="form-control" name="dt_begin" id="dt_begin" value="<?php echo date('Y-m-d')?>" >
  </div>

  <div class="form-group">
    <label for="">Minimum Value</label>
    <div class="input-group">
      <span class="input-group-addon">
        <input type="checkbox" class="chk_value" name="chk_min" id="chk_min" data-target="#nm_min" value="1" <?php echo $hasMin ?>/>
      </span>
      <input type="number" name="nm_min" id="nm_min" class="form-control" value="<?php echo $minVal ?>" step=".01" min="-999999.99" max="999999.99" placeholder="">

    </div>
  </div>

  <div class="form-group">
    <label for="">Maximum Value</label>
    <div class="input-group">
      <span class="input-group-addon">
        <input type="checkbox" class="chk_value" name="chk_max" id="chk_max" data-target="#nm_max" value="1" <?php echo $hasMax ?>/>
      </span>
      <input type="number" name="nm_max" id="nm_max" class="form-control" value="<?php echo $maxVal ?>" step=".01" min="-999999.99" max="999999.99" placeholder="">

    </div>
  </div>
  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
<script>
toggleInput($('#chk_min'));
toggleInput($('#chk_max'));

  $('.chk_value').click(function(event) {
    /* Act on the event */
    toggleInput($(this));
  });

  function toggleInput(el) {
    var target = el.data('target');
    if (el.is(':checked')) {
      $(target).removeAttr('disabled');
      $(target).attr('class', 'form-control');
    } else {
      $(target).attr('disabled', 'disabled');
      $(target).attr('class', 'form-control disabled');
    }
  }
</script>
