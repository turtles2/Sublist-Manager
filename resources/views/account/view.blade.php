@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">View Sublist Account's</div>

                <div class="panel-body">
									    <table class="table table-bordered" id="users-table">
								        <thead>
								            <tr>
								                <th>Employer</th>
								                <th>Created At</th>
								                <th>Updated At</th>
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
        ajax: '/data/viewaccount',
        columns: [
            { data: 'employer', name: 'employer' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' }
        ]
    });
});
</script>
@endsection