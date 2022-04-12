@extends('user.layouts.user')
@section('styles')
    <link href="{{ asset('select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Edit Task</h3>
        </div>
        <div class="card-body">
            @if (Auth::user()->id == $task->user_id )
            <form action="{{route('taskUpdate', $task->id)}}" method="POST">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <input type="text" name="title" class="form-control" value="{{$task->title}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <select name="priority" id="priority" class="form-control">
                                <option value="" disabled selected>Select Priority</option>
                                <option value="1" @if($task->priority == '1') selected @endif>1st</option>
                                <option value="2" @if($task->priority == '2') selected @endif>2nd</option>
                                <option value="3" @if($task->priority == '3') selected @endif>3rd</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <input type="date" name="start_date" class="form-control" value="{{$task->start_date}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <input type="date" name="end_date" class="form-control" value="{{$task->end_date}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <textarea type="text" name="description" class="form-control" rows="5">{{$task->description}}</textarea>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->hasRole(['director']))
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="users">Assign To</label>
                            <!-- v-model to bind multiple select -->
                            <select class="form-control users" name="director_users[]" multiple="multiselect" v-model="selectedUsers">
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
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
            @else
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <input type="text" name="title" class="form-control" value="{{$task->title}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <select name="priority" id="priority" class="form-control" disabled>
                                <option value="" disabled selected>Select Priority</option>
                                <option value="1" @if($task->priority == '1') selected @endif>1st</option>
                                <option value="2" @if($task->priority == '2') selected @endif>2nd</option>
                                <option value="3" @if($task->priority == '3') selected @endif>3rd</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <input type="date" name="start_date" class="form-control" value="{{$task->start_date}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <input type="date" name="end_date" class="form-control" value="{{$task->end_date}}" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <!-- old() function to prevent from previous data clear if cancelling -->
                            <textarea type="text" name="description" class="form-control" rows="5" disabled>{{$task->description}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="users">Assign To</label>
                            <!-- v-model to bind multiple select -->
                            <select class="form-control users" name="director_users[]" multiple="multiselect" v-model="selectedUsers" disabled>
                                @foreach($task->users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5>Task Performance</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('taskPerformance', $task->id)}}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-7">
                                    <label for="progress">Progress</label>
                                    <br>
                                    <input type="range" name="progress" class="mySlider" min="0" max="100" v-model="progress">
                                    <!-- cara untuk output vue object -->
                                    <span class="rangeValue">@{{progress}}</span>
                                </div>
                                <div class="col-md-2">
                                    <label for="status">Status</label>
                                    @if ($task->progress == 0)
                                        <p>No Progress</p>
                                    @endif
                                    @if ($task->progress > 0)
                                        @if($task->progress < 100)
                                            <p>In Progress</p>
                                        @endif
                                    @endif
                                    @if ($task->progress == 100)
                                        <p>Completed</p>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label for="file">File</label>
                                    <input type="file" name="file" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="result">Result</label>
                                    <textarea type="result" id="result" rows="3" class="form-control">{{$task->result}}</textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success d-inline">Submit Progress</button>
                                    <a href="{{route('taskInbox')}}" class="btn btn-danger d-inline">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('select2/select2.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.users').select2();
        });

        new Vue({
            el: '#app',
            data: {
                selectedUsers: {!! $task->users->pluck('id') !!},
                progress: {!! $task->progress !!}
            }
        });
    </script>
@endsection
