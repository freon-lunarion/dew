<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#holder" aria-controls="holder" role="tab" data-toggle="tab">Holder</a></li>
    <li role="presentation"><a href="#subordinate" aria-controls="subordinate" role="tab" data-toggle="tab">Subordinate</a></li>
    <li role="presentation"><a href="#supervisor" aria-controls="supervisor" role="tab" data-toggle="tab">Supervisor</a></li>
    <li role="presentation"><a href="#peer" aria-controls="peer" role="tab" data-toggle="tab">Peer</a></li>
    <li role="presentation"><a href="#asssignment" aria-controls="asssignment" role="tab" data-toggle="tab">Assignment</a></li>
    <li role="presentation"><a href="#managing" aria-controls="managing" role="tab" data-toggle="tab">Managing</a></li>
    <li role="presentation"><a href="#jobType" aria-controls="jobType" role="tab" data-toggle="tab">Job Type</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="holder">
      <dl class="">
        <dt>Id</dt>
        <dd>{holderId}
          <?php echo anchor($editHolder,'Change','class="btn btn-link" title="Change Holder"');?>
        </dd>
        <dt>Name</dt>
        <dd>{holderName} </dd>
      </dl>
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#hisHolder" aria-expanded="true" aria-controls="hisHolder">
                <i class="fa fa-chevron-right"></i> History of Holder
              </a>
            </h4>
          </div>
          <div id="hisHolder" class="panel-collapse collapse" role="tabpanel" aria-labelledby="hisHolder">
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
                  {holder}
                    <tr class="{historyRow}">
                      <td>{holderBegin}</td>
                      <td>{holderEnd}</td>
                      <td><a href="{viewPers}" class="btn btn-link" title="View Person">{holderId}</a></td>
                      <td><a href="{viewPers}" class="btn btn-link" title="View Person">{holderName}</a></td>
                    </tr>
                  {/holder}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div role="tabpanel" class="tab-pane" id="supervisor">
      <dl class="">
        <dt>Position ID</dt>
        <dd>{sprPostId} <?php echo anchor($editSpr,'Change','class="btn btn-link" title="Change Supervisor"');?></dd>
        <dt>Position Name</dt>
        <dd>{sprPostName}</dd>

      </dl>
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#hisSpr" aria-expanded="true" aria-controls="hisSpr">
                <i class="fa fa-chevron-right"></i> History of Supervisor
              </a>
            </h4>
          </div>
          <div id="hisSpr" class="panel-collapse collapse" role="tabpanel" aria-labelledby="hisSpr">
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
                  {spr}
                  <tr>
                    <th>{sprBegin}</th>
                    <th>{sprEnd}</th>
                    <th><a href="{viewPost}" class="btn btn-link" title="View Position">{sprId}</a></th>
                    <th><a href="{viewPost}" class="btn btn-link" title="View Position">{sprName}</a></th>

                  </tr>
                  {/spr}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div role="tabpanel" class="tab-pane" id="subordinate">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Begin</th>
            <th>End</th>
            <th>Change</th>

            <th>Post Id</th>
            <th>Post Name</th>
            <th>Delimit</th>

          </tr>
        </thead>
        <tbody>
          {sub}
            <tr class="{historyRow}">
              <td>{subBegin}</td>
              <td>{subEnd}</td>
              <td><a href="{chgRel}" class="btn btn-link" title="Change Date">Chg. Date</a>

              <td><a href="{viewPost}" class="btn btn-link" title="View Position">{subPostId}</a></td>
              <td><a href="{viewPost}" class="btn btn-link" title="View Position">{subPostName}</a></td>
              <td><a href="{remRel}" class="btn btn-link btn-delete" title="Delete">Delete</a>

            </tr>
          {/sub}
        </tbody>
      </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="peer">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Begin</th>
            <th>End</th>
            <th>Post Id</th>
            <th>Post Name</th>

          </tr>
        </thead>
        <tbody>
          {peer}
            <tr class="">
              <td>{peerBegin}</td>
              <td>{peerEnd}</td>
              <td><a href="{viewPost}" class="btn btn-link" title="View Position">{peerPostId}</a></td>
              <td><a href="{viewPost}" class="btn btn-link" title="View Position">{peerPostName}</a></td>
            </tr>
          {/peer}
        </tbody>
      </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="asssignment">
      <dl class="">
        <dt>Id</dt>
        <dd>{assId} <?php echo anchor($editAss,'Change','class="btn btn-link" title="Change Assigment"');?></dd>
        <dt>Name</dt>
        <dd>{assName} </dd>
      </dl>
      <!-- -->
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#hisAss" aria-expanded="true" aria-controls="hisAss">
                <i class="fa fa-chevron-right"></i> History of Assignment
              </a>
            </h4>
          </div>
          <div id="hisAss" class="panel-collapse collapse" role="tabpanel" aria-labelledby="hisAss">
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
                  {ass}
                    <tr class="{assRow}">
                      <td>{assBegin}</td>
                      <td>{assEnd}</td>
                      <td><a href="{viewOrg}" class="btn btn-link" title="View Organization">{assId}</a></td>
                      <td><a href="{viewOrg}" class="btn btn-link" title="View Organization">{assName}</a></td>
                    </tr>
                  {/ass}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- -->

    </div>
    <div role="tabpanel" class="tab-pane" id="managing">
      <dl class="">
        <dt>Id</dt>
        <dd>{manId} <?php echo anchor($editMan,'Change','class="btn btn-link" title="Change Managing"');?></dd>
        <dt>Name</dt>
        <dd>{manName} </dd>
      </dl>
      <!-- -->
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#hisMan" aria-expanded="true" aria-controls="hisMan">
                <i class="fa fa-chevron-right"></i> History of Managing
              </a>
            </h4>
          </div>
          <div id="hisMan" class="panel-collapse collapse" role="tabpanel" aria-labelledby="hisMan">
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
                  {man}
                    <tr class="manRow">
                      <td>{manBegin}</td>
                      <td>{manEnd}</td>
                      <td><a href="{viewOrg}" class="btn btn-link" title="View Organization">{manId}</a></td>
                      <td><a href="{viewOrg}" class="btn btn-link" title="View Organization">{manName}</a></td>
                    </tr>
                  {/man}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- -->

    </div>
    <div role="tabpanel" class="tab-pane" id="jobType">
      <dl class="">
        <dt>Id</dt>
        <dd>{jobId}
          <?php echo anchor($editJob,'Change','class="btn btn-link" title="Change Job"');?>
        </dd>
        <dt>Name</dt>
        <dd>{jobName} </dd>
      </dl>
      <!-- -->
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#hisJob" aria-expanded="true" aria-controls="hisJob">
                <i class="fa fa-chevron-right"></i> History of Job Type
              </a>
            </h4>
          </div>
          <div id="hisJob" class="panel-collapse collapse" role="tabpanel" aria-labelledby="hisJob">
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
                  {job}
                    <tr class="{historyRow}">
                      <td>{jobBegin}</td>
                      <td>{jobEnd}</td>

                      <td><a href="{viewJob}" class="btn btn-link" title="View Job">{jobId}</a></td>
                      <td><a href="{viewJob}" class="btn btn-link" title="View Job">{jobName}</a></td>
                    </tr>
                  {/job}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- -->
    </div>
  </div>
</div>
