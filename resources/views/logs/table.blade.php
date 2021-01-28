<div class="table-responsive">
    <table class="table mb-0 table-striped table-bordered table-sm" id="users-table">
        <thead class="thead-dark">
            <tr>
                <th class="text-center" style="width: 12%;">Tipo</th>
                <th class="text-center" style="width: 20%;">Usuário</th>
                <th>Descrição</th>
                <th class="text-center" style="width: 18%;">Data criação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td class="text-center">{!! $log->log_name !!}</td>
                    <td class="text-center">{!! $log->user !!}</td>
                    <td>{!! $log->description !!}</td>
                    <td class="text-center">{{ $log->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
