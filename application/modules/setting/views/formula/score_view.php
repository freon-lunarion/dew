<h2>Score</h2>
<p >
  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
</p>
<div class="row">
  <div class="col-xs-60">
    <a href="{addLink}" title="Add Score" class="btn btn-default">Add</a>
  </div>
</div>

<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Value</th>
      <th>Lower</th>
      <th>Upper</th>
      <th>Begin</th>
      <th>End</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    {score}
      <tr >
        <td>{scoreValue}</td>
        <td>{scoreLower}</td>
        <td>{scoreUpper}</td>
        <td>{scoreBegin}</td>
        <td>{scoreEnd}</td>
        <td>
          <a href="{scoreEdit}" class="btn btn-link" title="Edit Score"><i class="glyphicon glyphicon-pencil"></i></a>
          <a href="{scoreDelete}" class="btn btn-link btn-delete" title="Delete Score"><i class="glyphicon glyphicon-trash"></i></a>
        </td>
      </tr>
    {/score}
  </tbody>
</table>
