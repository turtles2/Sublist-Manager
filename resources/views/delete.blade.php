@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Delete Account</div>

                <div class="panel-body">
                         <form class="form-horizontal" method="POST" action="/delete" >
                        {{ csrf_field()}}
                        
                         <div class="form-group{{ $errors->has('delete') ? ' has-error' : '' }}">
                            <label for="delete" class="col-md-4 control-label">Are you Sure?</label>

                            <div class="col-md-6">
                                <select class="form-control" id="delete" name="delete" required>
    <option value='' selected disabled>Please Select an Option</option>
	<option value='Yes'>Yes Delete my Account</option>
  </select>

                                @if ($errors->has('delete'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('delete') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Delete Account
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