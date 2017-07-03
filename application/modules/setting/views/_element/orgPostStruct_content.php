<ol class="breadcrumb">
  {bc}
    <li><a href="#" class="nav-open" data-id="{id}">{name}</a></li>
  {/bc}
</ol>
<table class="table table-hover table-striped">
  <thead>
    <tr>
      <th>Type</th>
      <th>ID</th>
      <th>Name</th>
      <th>Begin</th>
      <th>End</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    {org}
      <tr >
        <td><i class="fa fa-sitemap" title="Organization"></i></td>
        <td>{id}</td>
        <td>{name}</td>
        <td>{begda}</td>
        <td>{endda}</td>
        <td><a href="#" class="btn btn-link nav-open" data-id="{id}">Open</a></td>

      </tr>
    {/org}

    {post}
      <tr >
        <td><i class="glyphicon glyphicon-pawn" title="Position"></i></td>
        <td>{id}</td>
        <td>{name}</td>
        <td>{begda}</td>
        <td>{endda}</td>

        <td><a href="#" class="btn btn-link nav-select" data-id="{id}" data-dismiss="modal" data-text="{id} - {name}">Select</a></td>
      </tr>
    {/post}
  </tbody>
</table>
