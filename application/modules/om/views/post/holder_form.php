<?php $this->load->view('_base/top');?>
<h1 class="page-header">Position <small>Change Holder</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <div class="form-group">
    <label for="dt_begin">Since </label>
    <input type="date" class="form-control" name="dt_begin" id="dt_begin" value="<?php echo date('Y-m-d')?>" >
  </div>



  <div class="form-group">
    <label for="txt_name">Employee</label>
    <input type="text" class="form-control" id="txt_search" placeholder="Type Employee's name">
  </div>
  <div class="form-group">
    <label for="txt_name">Weight</label>
    <input type="number" class="form-control" id="nm_weight" name="nm_weight" value="100">
  </div>
  <div id="emp_content">

  </div>
  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_base/bottom');?>
<script>
  function Searching() {
    var query = $('#txt_search').val();
    $.ajax({
      url: siteUrl + '/om/Ajax/ShowEmployeeSelection',
      type: 'POST',
      dataType: 'html',
      data: {query: query}
    })
    .done(function(respond) {
      $('#emp_content').html(respond);
      console.log("success");
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  }

  $('#txt_search').keyup(function(e){
    if(e.keyCode == 13){
      Searching();
    } else if ((e.keyCode >= 48 && e.keyCode <= 90) || (e.keyCode == 8 || e.keycode  == 46) && $('#txt_search').val().length > 1) {
      // keycode 0-9 + a-z
      Searching();

    }
  });
</script>
