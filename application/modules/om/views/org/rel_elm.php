<div>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#children" aria-controls="children" role="tab" data-toggle="tab">Children</a></li>
    <li role="presentation"><a href="#position" aria-controls="position" role="tab" data-toggle="tab">Position</a></li>
    <li role="presentation" class=""><a href="#parent" aria-controls="home" role="tab" data-toggle="tab">Parent</a></li>
    <li role="presentation"><a href="#chief" aria-controls="profile" role="tab" data-toggle="tab">Chief</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="children">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Begin</th>
            <th>End</th>
            <th>Change</th>
            <th>Id</th>
            <th>Name</th>
            <th>Delimit</th>
          </tr>
        </thead>
        <tbody>
          {children}
            <tr class="{historyRow}">
              <td>{childrenBegin}</td>
              <td>{childrenEnd}</td>
              <td><a href="{chgRel}" class="btn btn-link" title="Change Date">Chg. Date</a>
              <td><a href="{viewOrg}" class="btn btn-link" title="View Org">{childrenId}</a></td>
              <td><a href="{viewOrg}" class="btn btn-link" title="View Org">{childrenName}</a></td>
              <td><a href="{remRel}" class="btn btn-link btn-delete" title="Delete">Delete</a></td>
            </tr>
          {/children}
        </tbody>
      </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="position">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Begin</th>
            <th>End</th>
            <th>Change</th>
            <th>Id</th>
            <th>Name</th>
            <th>Delimit</th>
          </tr>
        </thead>
        <tbody>
          {post}
            <tr class="{historyRow}">
              <td>{postBegin}</td>
              <td>{postEnd}</td>
              <td><a href="{chgRel}" class="btn btn-link" title="Change Date">Chg. Date</a>
              <td><a href="{viewPost}" class="btn btn-link" title="View Position">{postId}</a></td>
              <td><a href="{viewPost}" class="btn btn-link" title="View Position">{postName}</a></td>
              <td><a href="{remRel}" class="btn btn-link btn-delete" title="Delete">Delete</a></td>
            </tr>
          {/post}
        </tbody>
      </table>
    </div>
    <div role="tabpanel" class="tab-pane " id="parent">

      <dl class="">
        <dt>ID</dt>
        <dd>{parentId} <?php echo anchor($editParent,'Change','class="btn btn-link" title="Change Parent"'); ?></dd>
        <dt>Name</dt>
        <dd>{parentName}</dd>
      </dl>

      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#hisParent" aria-expanded="true" aria-controls="hisParent">
                <i class="fa fa-chevron-right"></i> History of Parent
              </a>
            </h4>
          </div>
          <div id="hisParent" class="panel-collapse collapse" role="tabpanel" aria-labelledby="hisParent">
            <div class="panel-body">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Begin</th>
                    <th>End</th>
                    <th>Id</th>
                    <th>Name</th>
                  </tr>
                </thead>
                <tbody>
                  {parent}
                    <tr class="{historyRow}">
                      <td>{parentBegin}</td>
                      <td>{parentEnd}</td>
                      <td><a href="{viewOrg}" class="btn btn-link" title="View Org">{parentId}</a></td>
                      <td><a href="{viewOrg}" class="btn btn-link" title="View Org">{parentName}</a></td>
                    </tr>
                  {/parent}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div role="tabpanel" class="tab-pane" id="chief">
      <dl class="">
        <dt>Position ID</dt>
        <dd>{chiefPostId} <?php echo anchor($editChief,'Change','class="btn btn-link" title="Change Chief"'); ?></dd>
        <dt>Position Name</dt>
        <dd>{chiefPostName}</dd>
      </dl>
      <!-- -->
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#hisChief" aria-expanded="true" aria-controls="hisChief">
                <i class="fa fa-chevron-right"></i> History of Chief
              </a>
            </h4>
          </div>
          <div id="hisChief" class="panel-collapse collapse" role="tabpanel" aria-labelledby="hisChief">
            <div class="panel-body">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Begin</th>
                    <th>End</th>
                    <th>Position Id</th>
                    <th>Position Name</th>
                  </tr>
                </thead>
                <tbody>
                  {chief}
                    <tr class="{historyRow}">
                      <td>{chiefBegin}</td>
                      <td>{chiefEnd}</td>
                      <td><a href="{viewPost}" class="btn btn-link" title="View Position">{chiefId}</a></td>
                      <td><a href="{viewPost}" class="btn btn-link" title="View Position">{chiefName}</a></td>
                    </tr>
                  {/chief}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>
