@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">View Sublist Contact's</div>

                <div class="panel-body">
									    <table class="table table-bordered" id="users-table">
								        <thead>
								            <tr>
								            	<th>First Name</th>
								            	<th>Last Name</th>
								                <th>Phone</th>
								                <th>Email</th>
								                <th>Manager</th>
								                 <th>Join Date</th>
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
        ajax: '/data/viewcontacts',
        columns: [
          	{ data: 'fname', name: 'fname' },
          	{ data: 'lname', name: 'lname' },
            { data: 'phone', name: 'phone' },
            { data: 'email', name: 'email' },
            { data: 'manager', name: 'manager' },
            { data: 'join_date', name: 'join_date' },
        ]
    });
});
</script>
@endsection