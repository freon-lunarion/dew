<?php $this->load->view('_base/top.php'); ?>
<ul class="nav nav-pills nav-stacked">
  <li><?php echo anchor('Om/Job','Job')?></li>
  <li><?php echo anchor('Om/Org','Organization')?></li>
  <li><?php echo anchor('Om/Post','Position')?></li>
  <li><?php echo anchor('Om/Emp','Employee')?></li>
  <hr />
  <li><?php echo anchor('Exp/Search','Search')?></li>
  <hr />
  <li><?php echo anchor('Setup/Database/demo','Reset DB','id="reset_db"')?></li>

</ul>
<div class="row">
  <div class="col-xs-12">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  </div>
  <div class="col-xs-12">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  </div>
  <div class="col-xs-12">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  </div>
  <div class="col-xs-12">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  </div>
</div>
<?php $this->load->view('_base/bottom.php'); ?>
<script>
  $("#reset_db").click(function(event) {
    /* Act on the event */
    event.preventDefault();

    var url = $(this).attr('href');
    /* Act on the event */
    swal({
      title: "Are you sure?",
      text: 'Write "YES" to reset Database',
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
</script>
