<?php $this->load->view('_base/top.php'); ?>
<ul class="nav nav-pills nav-stacked">
  <li><?php echo anchor('Setting/Perspective','Perspective')?></li>
  <li><?php echo anchor('Setting/Measurement','Measurement')?></li>
  <li><?php echo anchor('Setting/Formula','Formula')?></li>
  <li><?php echo anchor('Setting/Score','Score')?></li>
</ul>
<?php $this->load->view('_base/bottom.php'); ?>
