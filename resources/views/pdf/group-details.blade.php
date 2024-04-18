<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Group Details</title>
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
        td, th{
            padding : 3px;
        }
        .text-center{
            text-align: center;
        }
    </style>
</head>
<body>
<h2 class="text-center">{{ $orgs->name }}</h2>
<div style="margin-top : 1rem">
    <table border="1" id="data-table-default" class="table table-striped table-bordered align-middle">
        <thead>
        <tr>
            <th data-orderable="false" class="text-center">ID</th>
            <th data-orderable="false" class="text-center">Name</th>
            <th data-orderable="false" class="text-center">Scan Interval</th>
            <th data-orderable="false" class="text-center">Devices</th>
            <th data-orderable="false" class="text-center">Policies</th>
        </tr>
        </thead>
        <tbody>
        @if($groups)
            @foreach($groups as $group)
                <tr>
                    <td class="text-center">{{ $group->id }}</td>
                    <td class="text-center">{{ ($group->name ? $group->name : 'Default') }}</td>
                    <td class="text-center">{{ floor($group->refresh_interval / 60) }} hours</td>
                    <td class="text-center">{{ $group->server_count }}</td>
                    <td class="text-center">{{ count($group->policies) }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
</body>
</html>