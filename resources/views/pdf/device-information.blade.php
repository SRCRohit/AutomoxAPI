<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Device Information</title>
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
@php
    $info = \Session::get('device_information');
@endphp
<div style="margin-top : 1rem">
    <table border="1" id="data-table-default" class="table table-striped table-bordered align-middle">
        <thead>
        <tr>
            @foreach($info as $key => $value)
                @if($value == 1)
                    <th data-orderable="false">{{ $key }}</th>
                @endif
            @endforeach
        </tr>
        </thead>
        <tbody>
        @if($devices)
            @foreach($devices as $device)
                <?php
                $detail = (array) $device->detail;
                ?>
                <tr>
                    @if($info['Device ID'])
                        <td>{{ $device->id }}</td>
                    @endif
                    @if($info['Device Name'])
                            <td>{{ ($device->display_name?$device->display_name:$device->custom_name) }}</td>
                    @endif
                    @if($info['OS'])
                            <td>{{ $device->os_family }} {{ $device->os_name }}</td>
                    @endif
                    @if($info['Need Attention'])
                            <td>
                                @if($device->needs_reboot)
                                    <span class="badge rounded-pill bg-danger">
                                            Needs Reboot
                                        </span>
                                @endif
                            </td>
                    @endif
                    @if($info['Disconnect Time'])
                        <td>
                            @php
                                $dt = new DateTime($device->last_disconnect_time, new DateTimeZone("UTC"));
                                echo $dt->format("d M Y H:i:s O");
                            @endphp
                        </td>
                    @endif
                    @if($info['Group Id'])
                        <td>{{ $device->server_group_id }}</td>
                    @endif
                    @if($info['Tags'])
                        <td>
                            @if($device->tags)
                                @foreach($device->tags as $tag)
                                    <span class="badge bg-yellow text-dark">{{ $tag }}</span>
                                @endforeach
                            @endif
                        </td>
                    @endif

                    @if($info['IP Address'])
                        <td>
                            @if($device->ip_addrs)
                                {{ $device->ip_addrs[0] }}
                            @endif
                        </td>
                    @endif

                    @if($info['OS Version'])
                        <td>{{ $device->os_version }}</td>
                    @endif
                    @if($info['Scheduled Patches'])
                        <td>{{ $device->pending_patches }}</td>
                    @endif
                    @if($info['Status'])
                        <td>
                            <p class="p-0 m-0">Device Status : {{ ucwords($device->status->device_status) }}</p>
                            <p class="p-0 m-0">Agent Status : {{ ucwords($device->status->agent_status) }}</p>
                            <p class="p-0 m-0">Policy Status : {{ ucwords($device->status->policy_status) }}</p>
                        </td>
                    @endif
                    @if($info['Agent Version'])
                        <td>{{ $device->agent_version }}</td>
                    @endif

                    @if($info['Disconnected For'])
                        <td>
                            @php
                                $start = new DateTime($device->last_disconnect_time, new DateTimeZone("UTC"));
                                $end = new DateTime("now", new DateTimeZone('UTC'));
                                echo ($start->diff($end)->days);
                            @endphp
                        </td>
                    @endif
                    @if($info['Last Logged In User'])
                        <td>{{ $device->last_logged_in_user }}</td>
                    @endif
                    @if($info['Active Directory OU'])
                        <td>-</td>
                    @endif
                    @if($info['Total Patched'])
                        <td>{{ $device->patches }}</td>
                    @endif
                    @if($info['Created'])
                        <td>
                            @php
                                $time = new DateTime($device->create_time, new DateTimeZone("UTC"));
                                echo $time->format("d M Y H:i:s");
                            @endphp
                        </td>
                    @endif
                    @if($detail)
                        @if(count($detail))
                            @if(isset($detail['RAM']))
                                    @if($info['RAM'])
                                        <td>
                                            {{ formatBytes($detail['RAM']) }}
                                        </td>
                                    @endif
                            @endif
                            @if(isset($detail['CPU']))
                                    @if($info['CPU'])
                                        <td>
                                            {{ $detail['CPU'] }}
                                        </td>
                                    @endif
                            @endif
                            @if(isset($detail['DISKS']))
                                @if(count($detail['DISKS']))
                                    @if($info['DISK SPACE'])
                                        <td>
                                            {{ formatBytes($detail['DISKS'][0]->SIZE) }}
                                        </td>
                                    @endif
                                @endif
                            @endif
                            @if(isset($detail['VOLUME']))
                                @if(count($detail['VOLUME']))
                                    @if($info['AVAILABLE SPACE'])
                                        <td>
                                            {{ formatBytes($detail['VOLUME'][0]->FREE) }}
                                        </td>
                                    @endif
                                @endif
                            @endif
                        @else
                            @if($info['RAM'])
                                <td></td>
                            @endif
                            @if($info['CPU'])
                                <td></td>
                            @endif
                            @if($info['DISK SPACE'])
                                <td></td>
                            @endif
                            @if($info['AVAILABLE SPACE'])
                                <td></td>
                            @endif
                        @endif
                    @else
                        @if($info['RAM'])
                            <td></td>
                        @endif
                        @if($info['CPU'])
                            <td></td>
                        @endif
                        @if($info['DISK SPACE'])
                            <td></td>
                        @endif
                        @if($info['AVAILABLE SPACE'])
                            <td></td>
                        @endif
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
</body>
</html>