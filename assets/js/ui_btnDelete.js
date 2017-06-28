$('body').on('click', '.btn-delete', function(event) {
  event.preventDefault();

  var url = $(this).attr('href');
  /* Act on the event */
  swal({
    title: "Are you sure?",
    text: 'Write "YES" to delete',
    type: "input",
    showCancelButton: true,
    closeOnConfirm: false,
    animation: "pop",
    inputPlaceholder: "YES"
  },
  function(inputValue){
    if (inputValue === false) return false;

    if (inputValue === "") {
      return false
    } else if (inputValue.toLowerCase() == 'yes') {
      window.location.replace(url);
    }
  });
});
