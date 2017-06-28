<?php $this->load->view('_base/top');?>

<?php echo anchor('','Main Menu', 'class="btn btn-default"');?>

<div class="container" style="margin-top: 8%;">
  <div class="col-md-6 col-md-offset-3">
  <div class="row">

    <div role="form" id="form-buscar">
      <div class="form-group">
        <div class="input-group">
        <input class="form-control" type="text" id="txt_search" name="txt_search" placeholder="Search..." required/>
        <span class="input-group-btn">
        <button class="btn btn-default" id="btn_search">
        <i class="glyphicon glyphicon-search" aria-hidden="true"></i> Search
        </button>
        </span>
        </div>
      </div>
    </div>
  </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Name</th>
            <th>View</th>
          </tr>
        </thead>
        <tbody id="result">

        </tbody>
      </table>
    </div>
  </div>
</div>
<?php $this->load->view('_base/bottom');?>
<script>
  function ShowResult() {
    var query = $('#txt_search').val();

    $('#result').html('');
    $.ajax({
      url: siteUrl + '/Exp/AjaxSearchResult',
      type: 'POST',
      dataType: 'json',
      data: {query: query}
    })
    .done(function(respond) {
      var len = respond.length;

      if (len > 0) {
        for (var i = 0; i < len; i++) {
          $('#result').append(
            '<tr><td>' + respond[i].id + '</td><td>' + respond[i].type + '</td><td>' + respond[i].name + '</td><td>' + respond[i].link +'</td></tr>'
          );
        }
      }
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  }

  $('#btn_search').click(function(event) {
    ShowResult();
  });


  $('#txt_search').keyup(function(e){
    if(e.keyCode == 13){
      ShowResult();
    } else if (e.keyCode >= 48 && e.keyCode <= 90 && $('#txt_search').val().length > 3) {
      // keycode 0-9 + a-z
      ShowResult();

    }
  });
</script>
