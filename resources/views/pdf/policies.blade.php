<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Policies</title>
    <style>
        *{
            font-family: 'Helvetica' ;
        }
        table{
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
            font-family: "Courier New";
        }
        th, td{
            padding: 3px;
        }
        .text-center{
            text-align: center;
        }
        .text-left{
            text-align: left;
        }
        .badge {
            color: white;
            padding: 4px 8px;
            text-align: center;
            border-radius: 10px;
        }
        .bg-green{
            background-color: #32a932;
        }
        .bg-danger{
            background-color: #ff5b57;
        }
    </style>
</head>
<body>
<h2 class="text-center">{{ $orgs->name }}</h2>
<div style="margin-top : 1rem">
    <table border="1" id="data-table-default" class="table table-striped table-bordered align-middle">
        <thead>
        <tr>
            <th data-orderable="false" class="text-left">ID</th>
            <th data-orderable="false" class="text-left">Name</th>
            <th data-orderable="false" class="text-center">Type</th>
            <th data-orderable="false" class="text-center">Group</th>
            <th data-orderable="false" class="text-center">Devices</th>
            <th data-orderable="false" class="text-center">Status</th>
            <th data-orderable="false" class="text-center">Created At</th>
        </tr>
        </thead>
        <tbody>
        @if($policies)
            @foreach($policies as $policy)
                <tr>
                    <td class="text-left">{{ $policy->id }}</td>
                    <td class="text-left">{{ $policy->name }}</td>
                    <td class="text-center">{{ ucwords(str_replace('_', '', $policy->policy_type_name)) }}</td>
                    <td class="text-center">{{ count($policy->server_groups) }}</td>
                    <td class="text-center">{{ $policy->server_count }}</td>
                    <td class="text-center"><span class="badge bg-{{ ($policy->status=='active'?'green':'danger') }}
                                rounded-pill">{{ ucwords
                                ($policy->status)
                                }}</span></td>
                    <td class="text-center">{{ explode('T', $policy->create_time)[0] }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
</body>
</html>