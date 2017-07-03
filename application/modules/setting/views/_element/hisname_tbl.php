<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading with-border" role="tab" id="">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#hisName" aria-expanded="true" aria-controls="hisName">
          <i class="fa fa-chevron-right"></i> History of Name
        </a>
      </h4>
    </div>
    <div id="hisName" class="panel-collapse collapse" role="tabpanel" aria-labelledby="hisName">
      <div class="panel-body">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Begin</th>
              <th>End</th>
              <th>Short</th>
              <th>Name</th>
            </tr>
          </thead>
          <tbody>
            {history}
            <tr class="{historyRow}">
              <td>{historyBegin}</td>
              <td>{historyEnd}</td>
              <td>{historyName}</td>
              <td>{historyShort}</td>
            </tr>
            {/history}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
