@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">View Shift Workers</div>

                <div class="panel-body">
                         <form class="form-horizontal" method="POST" action="/viewworkingshift">
                        {{ csrf_field()}}
                        
                         <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <label for="type" class="col-md-4 control-label">Select Shift</label>

                            <div class="col-md-6">
                                <select class="form-control" id="shift" name="shift" required>
   															 <option value='' selected disabled>Please Select an Option</option>
																	@foreach($shift_data as $data)
																	
																		<option value='{{$data['time_string']}}'>{{$data['readable_datetime']}}</option>
																	
																	@endforeach
 																 </select>

                                @if ($errors->has('shift'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('shift') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                   View Coworkers
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection