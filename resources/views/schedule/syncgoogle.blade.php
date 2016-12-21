@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Sync Schedule with Google Calendar</div>

                <div class="panel-body">
                         <form class="form-horizontal" method="POST" action="/syncgoogle">
                        {{ csrf_field()}}
                        
                         <div class="form-group{{ $errors->has('sync') ? ' has-error' : '' }}">
                            <label for="sync" class="col-md-4 control-label">Shifts to Sync</label>

                            <div class="col-md-6">
                                <select class="form-control" id="sync" name="sync" required>
    <option value='' selected disabled>Please Select an Option</option>
	<option value='Sync Sub Shifts'>Sync Sub Shifts</option>
	<option value='Sync Regular Shifts'>Sync Regular Shifts</option>
	<option value='Sync All Shifts'>Sync All Shifts</option>
  </select>

                                @if ($errors->has('sync'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('sync') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Sync 
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