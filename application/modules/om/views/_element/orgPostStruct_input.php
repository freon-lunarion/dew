<div class="input-group">
  <input type="hidden" class="form-control" id="hdn_parent" name="hdn_post" placeholder="" value="<?php echo $postId ?>">

  <input type="text" class="form-control" id="txt_post" name="txt_post" placeholder="" readonly="readonly" value="<?php echo $postId .' - '. $postName ?>">
  <span class="input-group-btn">
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#explor_post">
      <i class="glyphicon glyphicon-option-horizontal "></i>&nbsp;
    </button>

  </span>

</div>
