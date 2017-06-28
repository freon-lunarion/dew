
<table class="table table-hover table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Select</th>
    </tr>
  </thead>
  <tbody>
    {emp}
      <tr >
        <td>{id}</td>
        <td>{name}</td>
        <td><input type="radio" value="{id}" name="rd_emp"/></td>

      </tr>
    {/emp}


  </tbody>
</table>
