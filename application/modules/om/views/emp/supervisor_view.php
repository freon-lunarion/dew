<?php $this->load->view('_base/top');?>
<?php echo anchor($backLink,'Back','class="btn btn-default"');?>

<h1 class="page-header">Person <small>Supervisor</small></h1>

<dl class="">
  <dt>Person</dt>
  <dd>{persId} - {persName}</dd>
  <dt>Position</dt>
  <dd>{postId} - {postName}</dd>
</dl>

<h2>Supervisor</h2>
<dl class="">
  <dt>Position</dt>
  <dd><a href="{viewPost}" title="View Position" class="btn btn-link">{sprPostId} - {sprPostName}</a></dd>
  <dt>Person</dt>
  <dd><a href="{viewPers}" title="View Person" class="btn btn-link">{sprPersId} - {sprPersName}</a></dd>
</dl>

<?php $this->load->view('_base/bottom');?>
