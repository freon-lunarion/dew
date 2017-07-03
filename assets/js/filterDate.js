jQuery(document).ready(function($) {
  refreshData();

});

$('#btn_date_refresh').on('click', function(event) {
  event.preventDefault();
  /* Act on the event */
  var form  = $('#filter_date').children('div');
  var begDa = form.children('#dt_begin').val();
  var endDa = form.children('#dt_end').val();
  $.ajax({
    url: siteUrl+'/Ajax/SetFilterDate/',
    type: 'POST',
    data: {begDa: begDa, endDa:endDa}
  })
  .done(function() {
    console.log("success");
    refreshData();
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });
});

function refreshData() {
  $('.refresh_target').each(function(index, el) {
    var elm = $(this);
    var ajaxUrl = siteUrl + '/' + elm.data('ajax') ;
    $.ajax({
      url: ajaxUrl,
    })
    .done(function(respond) {
      elm.html(respond);
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  });
}
