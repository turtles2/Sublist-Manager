@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add Job Code</div>

                <div class="panel-body">
                         <form class="form-horizontal" method="POST" action="/newjob" >
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

                           <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                              <label for="code" class="col-md-4 control-label">Sublist Code</label>

                              <div class="col-md-6">
                                  <input id="code" type="number" min="0" class="form-control" name="code" value="{{ old('code') }}" required>

                                  @if ($errors->has('code'))
                                      <span class="help-block">
                                          <strong>{{ $errors->first('code') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>

                          <div class="form-group{{ $errors->has('loc') ? ' has-error' : '' }}">
                             <label for="loc" class="col-md-4 control-label">Location</label>

                             <div class="col-md-6">
                                 <select class="form-control" id="loc" name="loc" required>
                                     <option value='' selected disabled>Please Select an Option</option>

                                      @foreach ($locations as $location)
                                          <option value='{{$location->id}}'>{{$location->name}} {{$location->address}} {{$location->city}}</option>
                                      @endforeach

                                    </select>

                                    @if ($errors->has('loc'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('loc') }}</strong>
                                        </span>
                                    @endif

                                </div>
                            </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Add Job
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
