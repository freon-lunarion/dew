<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Value</th>
      <th>Category</th>
      <th>Lower</th>
      <th>Upper</th>
      <th>Begin</th>
      <th>End</th>
      <th>View</th>
    </tr>
  </thead>
  <tbody>
    {rows}
      <tr>
        <td style="background-color:{color}">{value}</td>
        <td style="color:{color}">{category}</td>
        <td>{lower}</td>
        <td>{upper}</td>
        <td>{begda}</td>
        <td>{endda}</td>
        <td>{viewlink}</td>
      </tr>
    {/rows}
  </tbody>
</table>
