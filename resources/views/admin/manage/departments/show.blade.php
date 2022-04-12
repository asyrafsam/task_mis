@extends('admin.layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <h3>Departments Details</h3>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('departmentsCreate')}}" class="btn btn-primary mb-2"><i class="fa fa-user-plus"></i>Edit Departments</a>
        </div>
    </div>
    <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="Department">Department</label>
                        <pre>{{$department->department}}</pre>
                    </div>
                    <div class="col-md-6">
                        <label for="Department">Department</label>
                        <pre>{{$department->organized_by}}</pre>
                    </div>
                </div>
            </div>
        </div>
@endsection