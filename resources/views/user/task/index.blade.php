@extends('user.layouts.user')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="text-center">List of assigned tasks</div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Assigned To</th>
                        <th>Performance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $loop->index + 1}}</td>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->start_date }}</td>
                        <td>{{ $task->end_date }}</td>
                        <td>
                            @foreach ($task->users as $user)
                                {{ $user->name }}{{ $task->users->count() > 1 ? ',' : ''}}
                            @endforeach
                        </td>
                        <td>
                            @if ($task->result != null) 
                                {{ $task->result }}
                            @else
                                Incomplete
                            @endif
                        </td>
                        <td>
                            <a href="{{route('taskShow', $task->id)}}" class="btn btn-secondary btn-sm"><i class="fa fa-info"></i></a>
                            <a href="{{route('taskEdit', $task->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                            <a href="" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection