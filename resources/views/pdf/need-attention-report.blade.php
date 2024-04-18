<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Need Attention Report</title>
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
            <th data-orderable="false" class="text-center">ID</th>
            <th data-orderable="false" class="text-center">Name</th>
            <th data-orderable="false" class="text-center">OS</th>
            <th data-orderable="false" class="text-center">Needs Reboot</th>
            <th data-orderable="false" class="text-center">Connected</th>
            <th data-orderable="false" class="text-center">Group Id</th>
        </tr>
        </thead>
        <tbody>
        @if(count($reports->devices))
            @foreach($reports->devices as $device)
                <tr>
                    <td class="text-center">{{ $device->id }}</td>
                    <td class="text-center">{{ ($device->name ? $device->name : $device->customName) }}</td>
                    <td class="text-center">{{ $device->os_family }}</td>
                    <td class="text-center">
                                    <span class="badge bg-{{ ($device->needsReboot?'danger':'green') }} rounded-pill">
                                        {{ ($device->needsReboot?'YES':'NO') }}
                                    </span>
                    </td>
                    <td class="text-center">
                                    <span class="badge bg-{{ ($device->connected?'green':'danger') }} rounded-pill">
                                        {{ ($device->connected?'YES':'NO') }}
                                    </span>
                    </td>
                    <td class="text-center">{{ $device->groupId }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
</body>
</html>