@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">New Location</div>

                <div class="panel-body">
                         <form class="form-horizontal" method="POST" action="/newloc" >
                        {{ csrf_field()}}



                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                               <label for="name" class="col-md-4 control-label">Name</label>

                               <div class="col-md-6">
                                   <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>

                                   @if ($errors->has('name'))
                                       <span class="help-block">
                                           <strong>{{ $errors->first('name') }}</strong>
                                       </span>
                                   @endif
                               </div>
                           </div>

                           <div class="form-group{{ $errors->has('add') ? ' has-error' : '' }}">
                              <label for="add" class="col-md-4 control-label">Street Address</label>

                              <div class="col-md-6">
                                  <input id="add" type="text" class="form-control" name="add" value="{{ old('add') }}" required>

                                  @if ($errors->has('add'))
                                      <span class="help-block">
                                          <strong>{{ $errors->first('add') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>

                          <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                             <label for="city" class="col-md-4 control-label">City</label>

                             <div class="col-md-6">
                                 <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" required>

                                 @if ($errors->has('city'))
                                     <span class="help-block">
                                         <strong>{{ $errors->first('city') }}</strong>
                                     </span>
                                 @endif
                             </div>
                         </div>

                         <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                            <label for="state" class="col-md-4 control-label">State</label>

                            <div class="col-md-6">
                                <input id="state" type="text" class="form-control" name="state" value="{{ old('state') }}" required>

                                @if ($errors->has('sate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
                            </div>
                         </div>

                         <div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
                            <label for="zip" class="col-md-4 control-label">Zip Code</label>

                            <div class="col-md-6">
                                <input id="zip" type="text" class="form-control" name="zip" value="{{ old('zip') }}" required>

                                @if ($errors->has('zip'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('zip') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>




                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Add Location
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
