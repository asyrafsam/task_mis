@extends('user.layouts.user')
@section('styles')
    <link href="{{ asset('select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Create New Task</h3>
        </div>
        <div class="card-body">
            <form action="{{route('taskStore')}}" method="POST">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <input type="text" name="title" class="form-control" value="{{old('title')}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <select name="priority" id="priority" class="form-control">
                                <option value="" disabled selected>Select Priority</option>
                                <option value="1">1st</option>
                                <option value="2">2nd</option>
                                <option value="3">3rd</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <input type="date" name="start_date" class="form-control" value="{{old('start_date')}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <input type="date" name="end_date" class="form-control" value="{{old('end_date')}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <textarea type="text" name="description" class="form-control" rows="5">{{old('description')}}</textarea>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->hasRole(['director']))
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="users">Assign To</label>
                            <select class="form-control users" name="director_users[]" multiple="multiselect">
                                @foreach($director_users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif
                @if(Auth::user()->hasRole(['manager']))
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="users">Assign To</label>
                            <select class="form-control users" name="manager_users[]" multiple="multiselect">
                                @foreach($manager_users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif
                <button type="submit" class="btn btn-primary">Create Task</button>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('select2/select2.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.users').select2();
        });
    </script>
@endsection
