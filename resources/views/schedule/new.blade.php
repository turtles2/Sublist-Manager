@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">New Sublist Account</div>

                <div class="panel-body">
                         <form class="form-horizontal" method="POST" action="/newschedule"  enctype="multipart/form-data">
                        {{ csrf_field()}}
                        
                         <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <label for="type" class="col-md-4 control-label">Schedule Type</label>

                            <div class="col-md-6">
                                <select class="form-control" id="type" name="type" required>
    <option value='' selected disabled>Please Select an Option</option>
	<option value='Lifeguarding'>Lifeguarding</option>
  </select>

                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('schedule') ? ' has-error' : '' }}">
                            <label for="schedule" class="col-md-4 control-label">File Upload</label>

                            <div class="col-md-6">
                                <label class="btn btn-primary" for="my-file-selector">
    <input id="my-file-selector" type="file" style="display:none;" name="schedule" id="schedule">
    Select CSV Schedule
</label>

                                @if ($errors->has('schedule'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('schedule') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Import Schedule
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