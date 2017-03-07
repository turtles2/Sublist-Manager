@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Preceding Shifts</div>

                <div class="panel-body">
									    <table class="table table-bordered" id="pre-table">
								        <thead>
								            <tr>
								            	<th>Starts</th>
								            	<th>Ends</th>
								                <th>Worker</th>
								                <th>Sub</th>
								            </tr>
								        </thead>
								    </table>
	                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Identical Shifts</div>

                <div class="panel-body">
									    <table class="table table-bordered" id="id-table">
								        <thead>
								            <tr>
								            	<th>Starts</th>
								            	<th>Ends</th>
								                <th>Worker</th>
								                <th>Sub</th>
								            </tr>
								        </thead>
								    </table>
	                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Over Lapping Shifts</div>

                <div class="panel-body">
									    <table class="table table-bordered" id="over-table">
								        <thead>
								            <tr>
								            	<th>Starts</th>
								            	<th>Ends</th>
								                <th>Worker</th>
								                <th>Sub</th>
								            </tr>
								        </thead>
								    </table>
	                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Following Shifts</div>

                <div class="panel-body">
									    <table class="table table-bordered" id="after-table">
								        <thead>
								            <tr>
								            	<th>Starts</th>
								            	<th>Ends</th>
								                <th>Worker</th>
								                <th>Sub</th>
								            </tr>
								        </thead>
								    </table>
	                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(function() {
    $('#pre-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/data/viewworking/{{$time['start']}}/{{$time['end']}}/pre',
        columns: [
          	{ data: 'starts', name: 'starts' },
          	{ data: 'ends', name: 'ends' },
            { data: 'worker', name: 'worker' },
            { data: 'sub', name: 'sub' },
        ]
    });
});
$(function() {
    $('#id-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/data/viewworking/{{$time['start']}}/{{$time['end']}}/id',
        columns: [
          	{ data: 'starts', name: 'starts' },
          	{ data: 'ends', name: 'ends' },
            { data: 'worker', name: 'worker' },
            { data: 'sub', name: 'sub' },
        ]
    });
});

$(function() {
    $('#over-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/data/viewworking/{{$time['start']}}/{{$time['end']}}/over',
        columns: [
          	{ data: 'starts', name: 'starts' },
          	{ data: 'ends', name: 'ends' },
            { data: 'worker', name: 'worker' },
            { data: 'sub', name: 'sub' },
        ]
    });
});

$(function() {
    $('#after-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/data/viewworking/{{$time['start']}}/{{$time['end']}}/after',
        columns: [
          	{ data: 'starts', name: 'starts' },
          	{ data: 'ends', name: 'ends' },
            { data: 'worker', name: 'worker' },
            { data: 'sub', name: 'sub' },
        ]
    });
});
</script>
@endsection