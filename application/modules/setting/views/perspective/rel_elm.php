<h2>Related Strategic Objective</h2>

<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Change</th>
      <th>Id</th>
      <th>Name</th>
      <th class="text-danger">Delete</th>
    </tr>
  </thead>
  <tbody>
    {so}
      <tr class="{historyRow}">
        <td>{soBegin}</td>
        <td>{soEnd}</td>
        <td><a href="{chgRel}" class="btn btn-link" title="Change Date">Chg. Date</a>
        <td><a href="{viewRel}" class="btn btn-link" title="View Position">{soId}</a></td>
        <td><a href="{viewRel}" class="btn btn-link" title="View Position">{soName}</a></td>
        <td><a href="{remRel}" class="btn btn-link btn-delete" title="Change Date">Delete</a>
          </td>
      </tr>
    {/so}
  </tbody>
</table>
