<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Manual Approval</title>
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
            padding : 3px;
        }
        .text-center{
            text-align: center;
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
            <th data-orderable="false">ID</th>
            <th data-orderable="false">Name</th>
            <th data-orderable="false">OS</th>
            <th data-orderable="false">Current Status</th>
            <th data-orderable="false">Created</th>
            <th data-orderable="false">Policy</th>
        </tr>
        </thead>
        <tbody>
        @if($approvals)
            @foreach($approvals as $approval)
                @if($approval->status)
                    <tr>
                        <td>{{ $approval->id }}</td>
                        <td>{{ $approval->software->display_name }}</td>
                        <td class="text-center">{{ $approval->software->os_family }}</td>
                        <td class="text-center">
                                        <span class="badge bg-danger rounded-pill">
                                            {{ ucwords($approval->status) }}
                                        </span>
                        </td>
                        <td class="text-center">{{ $approval->manual_approval_time }}</td>
                        <td class="text-center">{{ $approval->policy->name }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
</div>
</body>
</html>