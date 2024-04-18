@extends('layouts.app')

@section('css')
<style type="text/css">

</style>
@endsection

@section('javascript')
<script type="text/javascript">

</script>
@endsection

@section('content')
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">DASHBOARD</h4>
    </div>
    <div class="panel-body">
        <iframe src="https://calendar.google.com/calendar/embed?height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=en.indian%23holiday%40group.v.calendar.google.com&amp;color=%23125A12&amp;ctz=Asia%2FCalcutta" style="border-width:0" scrolling="no" width="100%" height="500" frameborder="0"></iframe>
    </div>
</div>
@endsection