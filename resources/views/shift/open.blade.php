@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">View Open Shift's</div>

                <div class="panel-body">
									    <table class="table table-bordered" id="users-table">
								        <thead>
								            <tr>
								            	<th>Starts</th>
								            	<th>Ends</th>
								                <th>Posted</th>
								                <th>Poster</th>
								                <th>Shift Type</th>
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
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/data/viewopenshift',
        columns: [
          	{ data: 'starts', name: 'starts' },
          	{ data: 'ends', name: 'ends' },
            { data: 'posted', name: 'posted' },
            { data: 'poster', name: 'poster' },
            { data: 'type', name: 'type' },
        ]
    });
});
</script>
@endsection