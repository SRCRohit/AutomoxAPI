<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Prepatch Report</title>
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
        .text-center{
            text-align: center;
        }
        .table-critical{
            background-color : rgba(246, 81, 163, .5);
        }
        .table-active{
            background-color: #e9ecef;
        }
        .table-info{
            background-color: #dbf0f7;
        }
        .table-success{
            background-color: #cceeee;
        }
        .table-warning{
            background-color: #fdebd1;
        }
        .table-danger{
            background-color: #ffdedd;
        }
    </style>
</head>
<body>
<h2 class="text-center">{{ $orgs->name }}</h2>
<div style="margin-top : 1rem">
    <table border="1" id="data-table-default" class="table table-striped table-bordered align-middle">
        <thead>
        <tr>
            <th data-orderable="false">Device ID</th>
            <th data-orderable="false">Name</th>
            <th data-orderable="false">Group</th>
            <th data-orderable="false">OS</th>
            <th data-orderable="false">Patches</th>
        </tr>
        </thead>
        <tbody>
        @if($reports)
            @if($reports->prepatch->total)
                @foreach($reports->prepatch->devices as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ $report->name }}</td>
                        <td>{{ $report->group }}</td>
                        <td>{{ $report->os_family }}</td>
                        <td style="width:60%">
                            <table class="table table-bordered m-0" border="1">
                                <tr>
                                    <th>Software Name</th>
                                    <th class="text-center">Severity</th>
                                    <th class="text-center">Create Time</th>
                                    <th class="text-center">Patch Time</th>
                                </tr>
                                @foreach($report->patches as $patch)
                                    <?php
                                    $row_color = 'table-active';
                                    if($patch->severity == 'low'){
                                        $row_color = 'table-success';
                                    }elseif($patch->severity == 'high'){
                                        $row_color = 'table-danger';
                                    }elseif($patch->severity == 'medium'){
                                        $row_color = 'table-warning';
                                    }elseif($patch->severity == 'critical'){
                                        $row_color = 'table-critical';
                                    }
                                    ?>
                                    <tr class="{{$row_color}}">
                                        <td style="width:66%">{{ $patch->name }}</td>
                                        <td class="text-center" style="width:10%">{{ ($patch->severity ? ucwords($patch->severity): 'Unknown') }}</td>
                                        <td class="text-center" style="width:12%">{{ explode('T', $patch->createTime)[0] }}</td>
                                        <td class="text-center" style="width:12%">{{ explode('T', $patch->patchTime)[0] }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                @endforeach
            @endif
        @endif
        </tbody>
    </table>
</div>
</body>
</html>