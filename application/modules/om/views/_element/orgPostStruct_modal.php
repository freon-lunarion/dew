<!-- Modal -->
<div class="modal fade" id="explor_post" tabindex="-1" role="dialog" aria-labelledby="post_structlLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Organization Position</h4>
      </div>
      <div class="modal-body" id="post_exp">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
  $('#explor_post').on('show.bs.modal', function (e) {
    StructOrgPost(1);
  })

  $('#explor_post').on('click', '.nav-open', function(event) {
    event.preventDefault();
    var id = $(this).data('id');
    StructOrgPost(id);
  });

  $('#explor_post').on('click', '.nav-select', function(event) {
    event.preventDefault();
    var id = $(this).data('id');
    var text = $(this).data('text');
    $('#txt_post').val(text);
    $('#hdn_post').val(id);
  });

  function StructOrgPost(id) {
    var ajaxUrl = siteUrl + '/Om/Ajax/ShowOrgStrucSelection' ;
    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'html',
      data: {id: id,mode:'post'}
    })
    .done(function(respond) {
      $('#post_exp').html(respond);
    });

  }
</script>
