<div class="bg-white shadow-sm mb-3">
  <div class="">
    <table class="table table-striped table-sm">
      <thead>
      <tr>
        <th>Name</th>
        <th>Last run</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>Clear sessions</td>
        <td>{{$log['api_sessions'] ?? null}}</td>
        <td>
          <a class="" href="{{route('cron.clear.sessions')}}">RUN</a>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</div>
