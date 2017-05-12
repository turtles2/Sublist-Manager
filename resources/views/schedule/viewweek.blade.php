@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">This Weeks Schedule</div>

                <div class="panel-body">
									    <table class="table table-bordered" id="shift_table">
								        <thead>
								            <tr>
                                                <th>Day</th>
								            	<th>Starts</th>
								            	<th>Ends</th>
								                <th>Sub Shift</th>
								                <th>Shift Type</th>
                                                <th>Shift Length</th>
                                                <th>Running Total</th>
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
    $('#shift_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/data/viewweek',
        columns: [
            { data: 'day', name: 'day' },
          	{ data: 'starts', name: 'starts' },
          	{ data: 'ends', name: 'ends' },
          		{ data: 'sub', name: 'sub' },
            { data: 'type', name: 'type' },
            { data: 'length', name: 'length' },
            { data: 'total', name: 'total' },
        ],
         order: [[ 6, "asc" ]]
    });
});
</script>
@endsection
