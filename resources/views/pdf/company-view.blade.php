<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Company User Count</title>
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
            <th data-orderable="false">Date</th>
            <th data-orderable="false">Company Name</th>
            <th data-orderable="false">Account Status</th>
            <th data-orderable="false">MSP</th>
            <th data-orderable="false">License Issue Date</th>
            <th data-orderable="false">No. of Licensed Issued</th>
        </tr>
        </thead>
        <tbody>
                @if($daterange)
            <?php
            // Extract start and end dates from the date range
            $dateRangeParts = explode(' - ', $daterange);
            $startDate = date('Y-m-d', strtotime($dateRangeParts[0]));
            $endDate = date('Y-m-d', strtotime($dateRangeParts[1]));
        
            // Get user counts for the company within the date range
            $UserCount = \App\Models\UserCount::where('company_id', $org)
                ->whereBetween('createdAt', [$startDate, $endDate])
                ->get();
        
            // Output table rows for each user count
            foreach ($UserCount as $UserCounts) {
                ?>
                <tr>
                    <td class="text-center">{{ $org }}</td>
                    <td class="text-center">{{ $UserCounts->createdAt->format('Y-m-d') }}</td>
                    <td class="text-center">{{ $orgs->name }}</td>
                    <td class="text-center">Active</td>
                    <td class="text-center">{{ $company_detail->name }}</td>
                    <td class="text-center">{{ explode('T', $orgs->create_time)[0] }}</td>
                    <td class="text-center">{{ $UserCounts->user_number }}</td>
                </tr>
                <?php
            }
            ?>
        @endif

        </tbody>
    </table>
</div>
</body>
</html>