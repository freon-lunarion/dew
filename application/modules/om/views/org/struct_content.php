<ol class="breadcrumb">
  {bc}
    <li><a href="#" class="nav-open" data-id="{id}">{name}</a></li>
  {/bc}
</ol>
<table class="table table-hover table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Begin</th>
      <th>End</th>
      <th>Open</th>
      <th>View</th>
    </tr>
  </thead>
  <tbody>
    {rows}
      <tr >
        <td>{id}</td>
        <td>{name}</td>
        <td>{begda}</td>
        <td>{endda}</td>
        <td><a href="#" class="btn btn-link nav-open" data-id="{id}">Open</a></td>
        <td>{viewlink}</td>
      </tr>
    {/rows}
  </tbody>
</table>
