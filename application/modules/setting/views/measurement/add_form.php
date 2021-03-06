<?php $this->load->view('_base/top');?>
<h1 class="page-header">Measurement <small>Add</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <?php $this->load->view('_element/add_form');?>
  <div class="form-group">
    <label for="">Minimum Value</label>
    <div class="input-group">
      <span class="input-group-addon">
        <input type="checkbox" class="chk_value" name="chk_min" id="chk_min" value="1" data-target="#nm_min"/>
      </span>
      <input type="number" name="nm_min" id="nm_min" class="form-control" value="0.00"  min="-999999.99" max="999999.99" placeholder="">

    </div>
  </div>

  <div class="form-group">
    <label for="">Maximum Value</label>
    <div class="input-group">
      <span class="input-group-addon">
        <input type="checkbox" class="chk_value" name="chk_max" id="chk_max" value="1" data-target="#nm_max"/>
      </span>
      <input type="number" name="nm_max" id="nm_max" class="form-control" value="0.00"  min="-999999.99" max="999999.99" placeholder="">

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
