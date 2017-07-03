<!-- Modal -->
<div class="modal fade" id="explor_org" tabindex="-1" role="dialog" aria-labelledby="org_structlLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Organization Structure</h4>
      </div>
      <div class="modal-body" id="org_exp">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
  $('#explor_org').on('show.bs.modal', function (e) {
    StructOrg(0);
  })

  $('#explor_org').on('click', '.nav-open', function(event) {
    event.preventDefault();
    var id = $(this).data('id');
    StructOrg(id);
  });

  $('#explor_org').on('click', '.nav-select', function(event) {
    event.preventDefault();
    var id = $(this).data('id');
    var text = $(this).data('text');
    $('#txt_org').val(text);
    $('#hdn_org').val(id);
  });

  function StructOrg(id) {
    var ajaxUrl = siteUrl + '/Om/Ajax/ShowOrgStrucSelection/' ;
    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'html',
      data: {id: id,mode: 'org'}
    })
    .done(function(respond) {
      $('#org_exp').html(respond);
    });

  }
</script>
