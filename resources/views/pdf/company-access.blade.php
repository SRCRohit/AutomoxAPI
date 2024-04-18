<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Company Access</title>
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
        tr:nth-child(even) {
            background-color: lightgrey;
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
                <th data-orderable="false">ID</th>
                <th data-orderable="false">Company Name</th>
                <th data-orderable="false">MSP</th>
                <th data-orderable="false">Name</th>
                <th data-orderable="false">Email Id</th>
                <th data-orderable="false">Role</th>
                <th data-orderable="false">License Issue Date</th>
                <th data-orderable="false">License Expiry</th>
            </tr>
            </thead>
            <tbody>
            @if($users)
                @foreach($users as $user)
                    <tr>
                        <td class="text-center">{{ $user->id }}</td>
                        <td class="text-center">{{ $orgs->name }}</td>
                        <td class="text-center">{{ $company_detail->name }}</td>
                        <td class="text-center">{{ $user->firstname }} {{ $user->lastname }}</td>
                        <td class="text-center">{{ $user->email }}</td>
                        <td class="text-center">{{ $user->rbac_roles[array_search($org, array_column($user->rbac_roles, 'organization_id'))]->name }}</td>
                        <td class="text-center">
                            {{ explode('T',$orgs->create_time)[0] }}
                        </td>
                        <td class="text-center">
                            {{ ($orgs->trial_end_time?explode('T',$orgs->trial_end_time)[0]:'N/A') }}
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</body>
</html>