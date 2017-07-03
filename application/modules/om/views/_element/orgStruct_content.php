<ol class="breadcrumb">
  {bc}
    <li><a href="#" class="nav-open" data-id="{id}">{name}</a></li>
  {/bc}
</ol>
<table class="table table-hover table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Short</th>
      <th>Name</th>
      <th>Begin</th>
      <th>End</th>
      <th>Open</th>
      <th>Select</th>
    </tr>
  </thead>
  <tbody>
    {org}
      <tr >
        <td>{id}</td>
        <td>{short}</td>
        <td>{name}</td>
        <td>{begda}</td>
        <td>{endda}</td>
        <td><a href="#" class="btn btn-link nav-open" data-id="{id}">Open</a></td>
        <td><a href="#" class="btn btn-link nav-select" data-id="{id}" data-dismiss="modal" data-text="{id} - {name}">Select</a></td>
      </tr>
    {/org}
  </tbody>
</table>
